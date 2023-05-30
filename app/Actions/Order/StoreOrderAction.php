<?php

namespace App\Actions\Order;

use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentImage;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $payment = $order->payment()->create([
            'amount' => $request->total,
            'type' => $request->payment['type']
        ]);


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

        foreach ($request->products as $_product) {
            $product = Product::where('id', $_product['id'])->first();
            $size = $_product['size'] === 'regular' ? $product->size : $_product['size']['name'];
            $order->products()->attach($product->id, ['quantity' => $_product['pieces'], 'size' => $size]);
        }
        $orders = $user->orders()->get();
        return [
            'message' => 'order success',
            'orders' => $orders
        ];
    }
}
