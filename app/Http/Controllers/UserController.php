<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if($validator->fails()) {
            return $this->error(['fields' => $validator->errors()], "The given data was invalid.", 422);
        }

        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'Data not found', 404);
        }

        $data = [
            'name' => $request->get("name"),
            'value' => $request->get("value")
        ];

        if(!empty($request->get("password"))) {
            $data['password'] = bcrypt($request->get("password"));
        }

        $user->update($data);

        return $this->success(['user' => $user], 'User updated', 200);
    }
}
