<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;

class StoreOrderAction
{

    public function handle(Request $request)
    {

        $user = Auth::user();
        $order = Order::create([
            'order_num' => 'ORDR_' . Str::slug(now()),
            'quantity' => $request->quantity,
            'total' => $request->total,
            'user_id' => $user->id,
            'type' => 'online',
            'status' => 'pending'
        ]);

        $order->payment()->create([
            'amount' => $request->payment
        ]);

        // $payment = Payment::create([
        //     'amount' => $request->payment,
        //     'order_id' => $order->id
        // ]);

        foreach ($request->products as $_product) {
            $product = Product::where('id', $_product['id'])->first();
            $order->products()->attach($product->id, ['quantity' => $_product['pieces']]);
        }
        $orders = $user->orders()->get();
        return [
            'message' => 'order success',
            'orders' => $orders
        ];
    }
}
