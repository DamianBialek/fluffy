<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success(['orders' => Order::all()], 'Data found');
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

        $order = Order::create([
            'vehicle_id' => $request->get("vehicle_id", null),
            'active' => $request->get("active", 0),
            'name' => $request->get("name", null),
            'note' => $request->get("note", null),
            'date' => $request->get("date", null),
            'finished_at' => $request->get("finished_at", null)
        ]);

        if($order) {
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
        $order = Order::find($id);

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

    protected function validate($request)
    {
        return Validator::make($request->all(), [
            'vehicle_id' => 'exists:customers_vehicles,id',
            'active' => 'numeric|in:0,1',
            'date' => 'date',
            'finished_at' => 'date'
        ]);
    }
}
