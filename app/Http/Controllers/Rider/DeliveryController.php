<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function __construct(public Delivery $delivery)
    {

    }
    public function index(){


        $user = Auth::user();

        $deliveries = $this->delivery->where('user_id', $user->id)->with(['transaction.order', 'location'])->get();

        return response($deliveries, 200);
    }
}
