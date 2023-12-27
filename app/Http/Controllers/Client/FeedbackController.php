<?php

namespace App\Http\Controllers\Client;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function __construct(Order $order){

    }

}
