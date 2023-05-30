<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request, AuthService $auth) {
       return $auth->register($request);
    }
    public function logout(Request $request, AuthService $auth){
        return $auth->logout($request);
    }
    public function login(LoginRequest $request, AuthService $auth) {
        return $auth->login($request);
    }
}
