<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{


    public function index () {
        $user = Auth::user();
        $transaction = Transaction::with(['order.cart'])->where('user_id', $user->id)->get();
        $orders = Order::where('status', 'pending')->get();

        return response([
            'transactions' => $transaction,
            'orders' => $orders,
            'total' => [
                'transactions' => $transaction->count(),
                'orders' => $orders->count(),
                'onlineTransaction' => $transaction->where('type', 'online')->count(),
                'walkinTransaction' => $transaction->where('type', 'walk_in')->count()
            ]
        ], 200);
    }
}
