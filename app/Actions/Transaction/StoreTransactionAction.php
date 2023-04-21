<?php

namespace App\Actions\Transaction;

use App\Models\Order;
use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreTransactionAction {

    public function handle(Request $request){
        
        $user = Auth::user();
        $order = Order::find($request->order_id);

        if($order == null) {
            return abort(404, 'Order Not Found');
        }

        $transaction = Transaction::create([
            'ref' => 'TR' . now() . $user->id,
            'order_id' => $order->id,
            'user_id' => $user->id
        ]);

        foreach($request->supplies as $id){
            $supply = Supply::find($id);
            $transaction->supplies()->attach($supply->id);
        }

        return $transaction;
    }
}