<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success(['services' => Service::all()], 'Data found');
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

        $service = Service::create([
            'name' => $request->get("name"),
            'price' => $request->get("price")
        ]);

        if($service) {
            return $this->success(['service' => $service], 'Service created', 201);
        }

        return $this->error([], 'Service not created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $service = Service::find($id);

        if($service) {
            return $this->success(['service' => $service], 'Data found');
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

        $service = Service::find($id);

        if(!$service) {
            return $this->error([], 'Data not found', 404);
        }

        $service->update([
            'name' => $request->get("name"),
            'price' => $request->get("price")
        ]);

        return $this->success(['service' => $service], 'Service updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if(!$service) {
            return $this->error([], 'Data not found', 404);
        }

        $service->delete();

        return $this->success([], 'Service deleted', 200);
    }

    protected function validate($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric'
        ]);
    }
}
