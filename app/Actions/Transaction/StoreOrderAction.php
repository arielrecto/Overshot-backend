<?php


namespace App\Actions\Transaction;

use App\Models\Order;
use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interface\TransactionInterface;

class StoreOrderAction  implements TransactionInterface
{

    public function store(Request $request)
    {
        $order = Order::find($request->order['id']);
        $user = Auth::user();

        $transaction = Transaction::create([
            'ref' => 'TRNS_' . $order->id . Str::slug($order->created_at),
            'user_id' => $user->id,
            'order_id' => $order->id,
            'type' => 'online'
        ]);

        foreach ($request->supplies as $_supply) {
            $supply = Supply::where('id', $_supply['id'])->first();
            $transaction->supplies()->attach($supply->id, ['quantity' => $_supply['quantity']]);
            $supply->update([
                'quantity' => $supply->quantity - $_supply['quantity']
            ]);
        }
        $order->update([
            'status' => 'processed'
        ]);

        $orders = Order::where('status', 'pending')->with('user', 'payment', 'products')->get();

        return [
            'orders' => $orders,
            'message' => 'Transaction Success'
        ];
    }
}
