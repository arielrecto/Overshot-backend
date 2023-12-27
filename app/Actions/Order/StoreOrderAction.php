<?php

namespace App\Actions\Order;

use App\Enums\PaymentStatus;
use App\Enums\PaymentStatusAndType;
use App\Models\Category;
use App\Models\Location;
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


        $addressData = $request->addressData ?? null;




        $order = Order::create([
            'order_num' => 'ORDR_' . Str::slug(now()),
            'quantity' => $request->quantity,
            'total' => $request->total,
            'user_id' => $user->id,
            'type' => 'online',
            'status' => 'pending'
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


        if ($addressData !== null) {
            $location = Location::create([
                'latitude' => $addressData['lat'],
                'longitude' => $addressData['lng'],
                'address' => $addressData['address'],
                'user_id' => $user->id,
            ]);

            $order->update([
                'location_id' => $location->id
            ]);
        }



        foreach ($request->products as $_product) {
            $size = $_product['size'] === 'regular' ? 'regular' : $_product['size']['name'];

            $order->products()->attach($_product['id'], ['quantity' => $_product['pieces'], 'size' => $size]);
        }



        $orders = $user->orders()->get();
        return [
            'message' => 'order success',
            'orders' => $orders
        ];
    }
}
