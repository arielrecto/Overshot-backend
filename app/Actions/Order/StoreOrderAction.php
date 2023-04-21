<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreOrderAction
{

    public function handle(Request $request)
    {

        $user = Auth::user();
        $order = Order::create([
            'quantity' => $request->quantity,
            'total' => $request->total,
            'user_id' => $user->id
        ]);

        foreach ($request->products as $product_name) {
            $product = Product::where('name', $product_name)->first();
            $product->orders()->attach($order->id);
        }
        return $order;
    }
}
