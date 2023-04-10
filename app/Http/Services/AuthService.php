<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register($request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken($user->name)->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function logout($request)
    {
        $response = $request->user()->tokens()->delete();

        if(!$response) {
         return abort(500);
        }

        return response([
            'message' => 'Logout Successfully'
        ], 200);
    }
    public function login($request) {
        if(!Auth::attempt($request->only(['email', 'password']))) {
            return abort(401);
        }
        $user = User::where('email', $request->email)->first();

        return response([
            'user' => $user,
            'message' => 'Login Successfully',
            'token' => $user->createToken($user->name)->plainTextToken
        ], 200);
    }
}
