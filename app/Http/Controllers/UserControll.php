<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserControll extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $user = Auth::user();

            $payload = [
                "email" => $validated["email"],
                "role" => $user->role,
                "iat" => Carbon::now()->timestamp,
                "exp" => Carbon::now()->timestamp + 60 * 60 * 2,
            ];

            $token = JWT::encode($payload, (string)env("JWT_SECRET_KEY"), "HS256");

            return response()->json([
                'msg' => 'Token has succesfully created',
                'data' => 'Bearer ' . $token
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Email or Password is wrong'
            ], 422);
        }
    }
}
