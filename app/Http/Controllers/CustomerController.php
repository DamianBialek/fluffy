<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success(['customers' => Customer::all()], 'Data found');
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

        return DB::transaction(function () use ($request) {
            $customer = Customer::create([
                'type' => $request->get("type"),
                'name' => $request->get("name"),
                'surname' => $request->get("surname")
            ]);

            if($customer) {
                if(!empty($request->get("vehicles"))) {
                    foreach ($request->get("vehicles") as $vehicle) {
                        if($cVehicle = CustomerVehicle::find($vehicle['id'])) {
                            $cVehicle->customer()->associate($customer)->save();
                        }
                    }
                }
                return $this->success(['customer' => $customer], 'Customer created', 201);
            }

            return $this->error([], 'Customer not created');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $customer = Customer::with("vehicles")->where("id", $id)->firstOrFail();

        if($customer) {
            return $this->success(['customer' => $customer], 'Data found');
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

        $customer = Customer::find($id);

        if(!$customer) {
            return $this->error([], 'Data not found', 404);
        }

        $customer->update([
            'type' => $request->get("type"),
            'name' => $request->get("name"),
            'surname' => $request->get("surname")
        ]);

        return $this->success(['customer' => $customer], 'Customer updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if(!$customer) {
            return $this->error([], 'Data not found', 404);
        }

        $customer->delete();

        return $this->success([], 'Customer deleted', 200);
    }

    protected function validate($request)
    {
        return Validator::make($request->all(), [
            'type' => 'required|in:natural_person,company',
            'name' => 'required|max:64',
            'surname' => 'required|max:64'
        ]);
    }
}
