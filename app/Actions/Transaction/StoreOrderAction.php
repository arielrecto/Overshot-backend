<?php


namespace App\Actions\Transaction;

use App\Models\User;
use App\Models\Order;
use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interface\TransactionInterface;
use App\Models\Delivery;
use App\Notifications\OrderStatus;

class StoreOrderAction  implements TransactionInterface
{

    public function store(Request $request)
    {


        $rider = User::find($request->riderId);



        $order = Order::find($request->order['id']);
        $user = Auth::user();


        $customer = User::find($order->user->id);




        $transaction = Transaction::create([
            'ref' => 'TRNS_' . $order->id . Str::slug($order->created_at),
            'user_id' => $user->id,
            'order_id' => $order->id,
            'type' => 'online'
        ]);


        $delivery = Delivery::create([
            'user_id' => $rider->id,
            'transaction_id' => $transaction->id,
            'location_id' => $order->location->id
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


        $orderStatusMessage = [
            'order_id' => $order->order_num,
            'status' => $order->status,
            'message' => "Your order now is processed and ready to delivery"
        ];

        $customer->notify(new OrderStatus($orderStatusMessage));


        $orders = Order::where('status', 'pending')->with('user', 'payment', 'products')->get();

        return [
            'orders' => $orders,
            'message' => 'Transaction Success'
        ];
    }
}
