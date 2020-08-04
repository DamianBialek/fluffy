<?php

namespace App\Http\Controllers;

use App\CustomerVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success(['vehicles' => CustomerVehicle::all()], 'Data found');
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

        $vehicle = CustomerVehicle::create([
            'customer_id' => $request->get("customer_id"),
            'vin' => $request->get("vin"),
            'registration_number' => $request->get("registration_number"),
            'mark' => $request->get("mark"),
            'model' => $request->get("model"),
            'production_year' => $request->get("production_year")
        ]);

        if($vehicle) {
            return $this->success(['vehicle' => $vehicle], 'Vehicle created', 201);
        }

        return $this->error([], 'Vehicle not created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vehicle = CustomerVehicle::find($id);

        if($vehicle) {
            return $this->success(['vehicle' => $vehicle], 'Data found');
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
        $vehicle = CustomerVehicle::find($id);

        if(!$vehicle) {
            return $this->error([], 'Data not found', 404);
        }

        $validator = $this->validate($request, $id);

        if($validator->fails()) {
            return $this->error(['fields' => $validator->errors()], "The given data was invalid.", 422);
        }

        $vehicle->update([
            'customer_id' => $request->get("customer_id"),
            'vin' => $request->get("vin"),
            'registration_number' => $request->get("registration_number"),
            'mark' => $request->get("mark"),
            'model' => $request->get("model"),
            'production_year' => $request->get("production_year")
        ]);

        return $this->success(['vehicle' => $vehicle], 'Vehicle updated', 200);
    }

    public function getUnassignedVehicles(Request $request)
    {
        $query = CustomerVehicle::where("customer_id", null);

        if(!empty($request->get("query"))) {
            $queryString = $request->get("query");
            $query->where(function ($query) use($queryString) {
                $query->orWhere("vin", "LIKE", "%{$queryString}%");
                $query->orWhere("registration_number", "LIKE", "%{$queryString}%");
                $query->orWhere("mark", "LIKE", "%{$queryString}%");
                $query->orWhere("model", "LIKE", "%{$queryString}%");
                $query->orWhere("production_year", "LIKE", "%{$queryString}%");
            });
        }

        $vehicles = $query->get();

        return $this->success(['vehicles' => $vehicles]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $parameter = CustomerVehicle::find($id);

        if(!$parameter) {
            return $this->error([], 'Data not found', 404);
        }

        $parameter->delete();

        return $this->success([], 'Vehicle deleted', 200);
    }

    protected function validate($request, $exceptId = null)
    {
        $rules = [
            'customer_id' => 'nullable|exists:customers,id',
            'vin' => 'required|max:191|unique:customers_vehicles,vin',
            'registration_number' => 'required|max:64',
            'mark' => 'required|max:45',
            'model' => 'required|max:45',
            'production_year' => 'required|numeric|digits:4'
        ];

        if(!empty($exceptId)) {
            $rules['vin'] .= ','.$exceptId;
        }

        return Validator::make($request->all(), $rules);
    }
}
