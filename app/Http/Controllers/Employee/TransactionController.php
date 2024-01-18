<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use App\Models\Order;
use App\Models\Supply;
use App\Models\Product;
use App\Models\Delivery;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Transaction\StorePosAction;
use App\Actions\Employee\GetItemsDataAction;
use App\Actions\Transaction\StoreOrderAction;
use App\Actions\Transaction\StoreTransactionAction;
use App\Http\Requests\Employee\StoreTransactionRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use GuzzleHttp\Promise\Create;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetItemsDataAction $getItemsDataAction)
    {
        $items = $getItemsDataAction->handle();

        return response($items, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function order(Request $request, StoreOrderAction $storeOrderAction)
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

        $order->update([
            'status' => 'processed'
        ]);


        $orderStatusMessage = [
            'order_id' => $order->order_num,
            'status' => $order->status,
            'message' => "Your order now is processed and ready to delivery"
        ];

        $customer->notify(new OrderStatus($orderStatusMessage));


        $orders = Order::where('status', 'pending')->with([
            'user',
            'cart' => function($cart){
                $cart->with(['cartProducts' => function($c_product){
                    $c_product->with([
                        'product.image',
                    ]);
                }]);
            },
            'payment',
            'location'
            ])->latest()->get();


        return response([
            'orders' => $orders,
            'message' => 'Transaction Success'
        ]);
    }
    public function pointOfSale(Request $request, StorePosAction $storePosAction)
    {
        $user = Auth::user();

        $cart = Cart::create([
            'ref' => 'CRT-' . Str::slug(now()),
            'quantity' => $request->totalItem,
            'total' => $request->total,
            'user_id' => $user->id,
        ]);

        $order = Order::create([
            'order_num' => 'ORDR_' . Str::slug(now()),
            'user_id' => $user->id,
            'type' => 'walk_in',
            'status' => 'processed',
            'cart_id' => $cart->id
        ]);

        $order->payment()->create([
            'amount' => $request->payment,
            'type' => 'cash'
        ]);

        foreach ($request->products as $_product) {


            CartProduct::create([
                'price' => $_product['sizes'][0]['pivot']['price'],
                'size' => json_encode($_product['size']),
                'addons' => json_encode([]),
                'sugar_level' => '90',
                'product_id' => $_product['id'],
                'cart_id' => $cart->id,
                'quantity' => $request->totalItem
            ]);
            // $product = Product::where('id', $_product['id'])->first();
            // $size = $_product['size'] === 'regular' ? 'regular' : $_product['size']['name'];
            // $order->products()->attach($product->id, ['quantity' => $_product['quantity'], 'size' => $size]);
        }

        $transaction = Transaction::create([
            'ref' => 'TRNS_' . $order->id . Str::slug($order->created_at),
            'user_id' => $user->id,
            'order_id' => $order->id,
            'type' => 'walk_in'
        ]);

        // foreach ($request->supplies as $_supply) {
        //     $supply = Supply::where('id', $_supply['id'])->first();
        //     $transaction->supplies()->attach($supply->id, ['quantity' => $_supply['quantity']]);
        //     $supply->update([
        //         'quantity' => $supply->quantity - $_supply['quantity']
        //     ]);
        // }


       $cart->update(['is_check_out' => true]);

        $products = Product::with('image')->get();
        $supplies = Supply::get();

        return response([
            'products' => $products,
            'supplies' => $supplies
        ], 200);
    }
}
