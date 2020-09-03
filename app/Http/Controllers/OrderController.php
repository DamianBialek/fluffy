<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderPosition;
use App\Pdf\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if(!empty($request->get("query")))
            $ordersData = Order::search($request->get("query"));
        else
            $ordersData = Order::query();

        $ordersData = $ordersData->paginate(30);
        $paginationData = $ordersData->toArray();
        unset($paginationData['data']);

        return $this->success(['orders' => $ordersData->items(), 'pagination' => $paginationData], 'Data found');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = $this->validate($request);

        if($validator->fails()) {
            return $this->error(['fields' => $validator->errors()], "The given data was invalid.", 422);
        }

        $order = new Order([
            'vehicle_id' => $request->get("vehicle_id", null),
            'active' => $request->get("active", 0),
            'name' => $request->get("name", null),
            'note' => $request->get("note", null),
            'date' => $request->get("date", null),
            'finished_at' => $request->get("finished_at", null)
        ]);

        $order->generateAndSetNewNumber();
        $order->save();

        if($order) {
            if(!empty($request->get("positions"))) {
                $positions = [];
                foreach ($request->get("positions") as $position) {
                    $positions[] = new OrderPosition([
                        'name' => $position["name"],
                        'price' => $position["price"],
                        'quantity' => $position["quantity"]
                    ]);
                }

                $order->positions()->saveMany($positions);
            }

            return $this->success(['order' => $order], 'Order created', 201);
        }

        return $this->error([], 'Order not created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $order = Order::with("vehicle", "positions")->where("id", $id)->firstOrFail();

        if($order) {
            return $this->success(['order' => $order], 'Data found');
        }

        return $this->error([], 'Data not found', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validate($request);

        if($validator->fails()) {
            return $this->error(['fields' => $validator->errors()], "The given data was invalid.", 422);
        }

        $order = Order::find($id);

        if(!$order) {
            return $this->error([], 'Data not found', 404);
        }

        $order->update([
            'vehicle_id' => $request->get("vehicle_id", null),
            'active' => $request->get("active", 0),
            'name' => $request->get("name", null),
            'note' => $request->get("note", null),
            'date' => $request->get("date", null),
            'finished_at' => $request->get("finished_at", null)
        ]);

        return $this->success(['order' => $order], 'Order updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if(!$order) {
            return $this->error([], 'Data not found', 404);
        }

        $order->delete();

        return $this->success([], 'Order deleted', 200);
    }

    public function addPosition($id, Request $request)
    {
        $validator = $this->validateOrderPosition($request);

        if($validator->fails()) {
            return $this->error(['fields' => $validator->errors()], "The given data was invalid.", 422);
        }

        $order = Order::find($id);

        if($order) {
            $position = $order->positions()->create([
                'name' => $request->get("name"),
                'price' => $request->get("price"),
                'quantity' => $request->get("quantity", 1)
            ]);

            return $this->success(['position' => $position], 'Data found');
        }

        return $this->error([], 'Data not found', 404);
    }

    public function updatePosition($id, $positionId, Request $request)
    {
        $position = Order::find($id)->positions()->find($positionId);

        if($position) {
            $validator = $this->validateOrderPosition($request);

            if($validator->fails()) {
                return $this->error(['fields' => $validator->errors(), 'position' => $position], "The given data was invalid.", 422);
            }

            $position->update([
                'name' => $request->get("name"),
                'price' => $request->get("price"),
                'quantity' => $request->get("quantity", 1)
            ]);

            return $this->success(['position' => $position], 'Position updated');
        }

        return $this->error([], 'Data not found', 404);
    }

    public function destroyPosition($id, $positionId)
    {
        $order = Order::find($id);

        if(!$order) {
            return $this->error([], 'Data not found', 404);
        }

        $res = $order->positions()->where("id", $positionId)->delete();

        if($res) {
            return $this->success([], 'Position deleted');
        } else {
            return $this->error([], "Position not found", 404);
        }
    }

    public function generatePdf($id)
    {
        $order = Order::with("positions")->where("id", $id)->firstOrFail();

        if(!$order) {
            return $this->error([], 'Data not found', 404);
        }

        $data = [
            'date' => date("d-m-y"),
            'positions' => $order->positions
        ];

        return response((Pdf::loadView("pdf.orderInvoice", $data))->Output());
    }

    protected function validateOrderPosition($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);
    }

    protected function validate($request)
    {
        return Validator::make($request->all(), [
            'vehicle_id' => 'exists:customers_vehicles,id',
            'active' => 'numeric|in:0,1',
            'date' => 'date|nullable',
            'finished_at' => 'date|nullable'
        ]);
    }
}
