<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllerActionException;
use App\Order;
use App\OrderService;
use App\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $order = Order::create([
            'vehicle_id' => $request->get("vehicle_id", null),
            'active' => $request->get("active", 0),
            'name' => $request->get("name", null),
            'note' => $request->get("note", null),
            'date' => $request->get("date", null),
            'finished_at' => $request->get("finished_at", null)
        ]);

        if($order) {
            if(!empty($request->get("services"))) {
                $services = [];
                foreach ($request->get("services") as $service) {
                    $services[] = new OrderService([
                        'name' => $service["name"],
                        'price' => $service["price"],
                        'quantity' => $service["quantity"]
                    ]);
                }

                $order->services()->saveMany($services);
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
        $order = Order::with("vehicle", "services")->where("id", $id)->firstOrFail();

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

    public function addService($id, Request $request)
    {
        $validator = $this->validateOrderService($request);

        if($validator->fails()) {
            return $this->error(['fields' => $validator->errors()], "The given data was invalid.", 422);
        }

        $order = Order::find($id);

        if($order) {
            $service = $order->services()->create([
                'name' => $request->get("name"),
                'price' => $request->get("price"),
                'quantity' => $request->get("quantity", 1)
            ]);

            return $this->success(['service' => $service], 'Data found');
        }

        return $this->error([], 'Data not found', 404);
    }

    public function updateService($id, $serviceId, Request $request)
    {
        $service = Order::find($id)->services()->find($serviceId);

        if($service) {
            $validator = $this->validateOrderService($request);

            if($validator->fails()) {
                return $this->error(['fields' => $validator->errors(), 'service' => $service], "The given data was invalid.", 422);
            }

            $service->update([
                'name' => $request->get("name"),
                'price' => $request->get("price"),
                'quantity' => $request->get("quantity", 1)
            ]);

            return $this->success(['service' => $service], 'Service updated');
        }

        return $this->error([], 'Data not found', 404);
    }

    public function destroyService($id, $serviceId)
    {
        $order = Order::find($id);

        if(!$order) {
            return $this->error([], 'Data not found', 404);
        }

        $res = $order->services()->where("id", $serviceId)->delete();

        if($res) {
            return $this->success([], 'Service deleted');
        } else {
            return $this->error([], "Service not found", 404);
        }
    }

    protected function validateOrderService($request)
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
