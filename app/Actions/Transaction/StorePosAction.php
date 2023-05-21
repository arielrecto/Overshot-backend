<?php


namespace App\Actions\Transaction;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interface\TransactionInterface;
use App\Models\Supply;

class StorePosAction implements TransactionInterface{


    public function store (Request $request){


        $user = Auth::user();

        $order = Order::create([
            'order_num' => 'ORDR_' . Str::slug(now()),
            'quantity' => $request->totalItem,
            'total' => $request->total,
            'user_id' => $user->id,
            'type' => 'walk_in',
            'status' => 'processed'
        ]);

        $order->payment()->create([
            'amount' => $request->payment
        ]);

        foreach ($request->products as $_product) {
            $product = Product::where('id', $_product['id'])->first();
            $order->products()->attach($product->id, ['quantity' => $_product['quantity']]);
        }

        $transaction = Transaction::create([
            'ref' => 'TRNS_' . $order->id . Str::slug($order->created_at),
            'user_id' => $user->id,
            'order_id' => $order->id,
            'type' => 'walk_in'
        ]);

        foreach ($request->supplies as $_supply) {
            $supply = Supply::where('id', $_supply['id'])->first();
            $transaction->supplies()->attach($supply->id, ['quantity' => $_supply['quantity']]);
            $supply->update([
                'quantity' => $supply->quantity - $_supply['quantity']
            ]);
        }
        return $transaction;
    }

}
