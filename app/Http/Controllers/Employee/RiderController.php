<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RiderController extends Controller
{
    public function index(){
        $riders = User::role('rider')->get();


        return response($riders, 200);
    }
}
