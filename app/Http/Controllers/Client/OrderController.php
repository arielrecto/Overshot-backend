<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\Size;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Location;
use App\Models\Customize;
use Illuminate\Support\Str;
use App\Models\PaymentImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Order\StoreOrderAction;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Node\Query\OrExpr;
use App\Http\Requests\Client\StoreOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        $orders = Order::with(['payment', 'user', 'cart' => function($cart) {
            $cart->with([
                'cartProducts' => function($c_product){
                    $c_product->with('product.image');
                }
            ]);
        }])->where('user_id', $user->id)->latest()->get();


        return response([
            'orders' => $orders
        ]);
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
    public function store(Request $request, StoreOrderAction $storeOrderAction)
    {

        $user = Auth::user();

        $customer = User::find($user->id);
        $cart = Cart::find($request->id);

        $order = Order::create([
            'order_num' => 'ORDR_' . Str::slug(now()),
            'quantity' => $request->quantity,
            'total' => $request->total,
            'user_id' => $user->id,
            'type' => 'online',
            'status' => 'pending',
            'cart_id' => $cart->id
        ]);

        $location = Location::create([
            'latitude' => $request->location['lat'],
            'longitude' => $request->location['lng'],
            'address' => $request->location['address'],
            'user_id' => $user->id,
        ]);

        $order->update([
            'location_id' => $location->id
        ]);

        $payment = Payment::create([
            'amount' => $request->total,
            'type' => $request->payment['type'],
            'status' => 'Gcash' == $request->payment['type'] ? 'Paid' : 'Unpaid',
            'order_id' => $order->id
        ]);



        if ($request->payment['type'] !== 'COD') {

            $image = $request->payment['image'];  // your base64 encoded


            $_image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
            $_image = str_replace('data:image/png;base64,', '', $image);
            $fileContent = file_get_contents($image);
            $_image = str_replace(' ', '+', $image);
            $_image = preg_replace('#data:image/[^;]+;base64,#', '', strval($image));
            $imageName =  'Img' . now() . '.' . 'png';
            $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $imageName);



            $imageDecoded = base64_decode($_image);

            PaymentImage::create([
                'name' => $imageName,
                'url' => asset('storage/payment/image/' . $filename),
                'payment_id' => $payment->id
            ]);
            Storage::disk('public')->put('payment/image/' . $filename, $imageDecoded);
        }

        $orderStatusMessage = [
            'order_id' => $order->order_num,
            'status' => $order->status,
            'message' => "Your order will be process by our employee"
        ];


        $customer->notify(new OrderStatus($orderStatusMessage));

        $cart->update([
            'is_check_out' => true
        ]);

        return response(['message' => 'order Success'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $order = Order::with(['products' => function($q) use ($id){
        //     $q->with(['image', 'customizes' => function ($query) use ($id) {
        //         $query->where('order_id', $id);
        //     }])->withAvg('ratings', 'rate');
        // }, 'transaction.delivery', 'location'])->where('id', $id)->first();

        $order = Order::with(['location', 'transaction.delivery' , 'cart' => function($cart){
            $cart->with(['cartProducts' => function($c_product){
                $c_product->with(['product' => function ($product) {
                    $product->with(['image'])->withAvg('ratings', 'rate');
                }]);
            }]);
        }])->whereId($id)->first();
        return response($order, 200);
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
}
