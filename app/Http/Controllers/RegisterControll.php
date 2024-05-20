<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterControll extends Controller
{
    //
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|max:255',
            'role' => 'required|in:user,admin'
            
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        User::create([
            'name'=> $payload['name'],
            'email'=> $payload['email'],
            'password'=> bcrypt($payload['password']),
            'role' => $payload['role'],
        ]);

        return response()->json([
            'msg' => 'Account successfully created'
        ]);
    }
}
