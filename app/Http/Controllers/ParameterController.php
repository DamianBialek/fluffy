<?php

namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success(['parameters' => Parameter::all()], 'Data found');
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

        $parameter = Parameter::create([
            'name' => $request->get("name"),
            'value' => $request->get("value")
        ]);

        if($parameter) {
            return $this->success(['parameter' => $parameter], 'Parameter created', 201);
        }

        return $this->error([], 'Parameter not created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $parameter = Parameter::find($id);

        if($parameter) {
            return $this->success(['parameter' => $parameter], 'Data found');
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

        $parameter = Parameter::find($id);

        if(!$parameter) {
            return $this->error([], 'Data not found', 404);
        }

        $parameter->update([
            'name' => $request->get("name"),
            'value' => $request->get("value")
        ]);

        return $this->success(['parameter' => $parameter], 'Parameter updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $parameter = Parameter::find($id);

        if(!$parameter) {
            return $this->error([], 'Data not found', 404);
        }

        $parameter->delete();

        return $this->success([], 'Parameter deleted', 200);
    }

    protected function validate($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'value' => 'required'
        ]);
    }
}
