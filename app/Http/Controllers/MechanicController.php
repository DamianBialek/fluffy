<?php

namespace App\Http\Controllers;

use App\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $mechanicsData = Mechanic::paginate(30);
        $paginationData = $mechanicsData->toArray();
        unset($paginationData['data']);

        return $this->success(['mechanics' => $mechanicsData->items(), 'pagination' => $paginationData], 'Data found');
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

        $mechanic = Mechanic::create([
            'name' => $request->get("name"),
            'surname' => $request->get("surname")
        ]);

        if($mechanic) {
            return $this->success(['mechanic' => $mechanic], 'Mechanic created', 201);
        }

        return $this->error([], 'Mechanic not created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $mechanic = Mechanic::find($id);

        if($mechanic) {
            return $this->success(['mechanic' => $mechanic], 'Data found');
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

        $mechanic = Mechanic::find($id);

        if(!$mechanic) {
            return $this->error([], 'Data not found', 404);
        }

        $mechanic->update([
            'name' => $request->get("name"),
            'surname' => $request->get("surname")
        ]);

        return $this->success(['mechanic' => $mechanic], 'Mechanic updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $mechanic = Mechanic::find($id);

        if(!$mechanic) {
            return $this->error([], 'Data not found', 404);
        }

        $mechanic->delete();

        return $this->success([], 'Mechanic deleted', 200);
    }

    protected function validate($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|max:45',
            'surname' => 'required|max:45'
        ]);
    }
}
