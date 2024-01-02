<?php

namespace App\Http\Controllers\Rider;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Delivery;
use App\Models\Location;
use App\Models\PaymentImage;
use Illuminate\Http\Request;
use App\Enums\DeliveryStatus;
use App\Notifications\OrderStatus;
use App\Enums\PaymentStatusAndType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    public function __construct(public Delivery $delivery)
    {

    }
    public function index(){


        $user = Auth::user();

        $deliveries = $this->delivery->where('user_id', $user->id)
        ->where('status', '!=', DeliveryStatus::DONE->value)
        ->with(['transaction.order.payment', 'location'])->get();

        return response($deliveries, 200);
    }
    public function acceptDelivery(Request $request, String $id){

        $user = Auth::user();


        $riderLocation = Location::create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => 'N\A',
            'user_id' => $user->id
        ]);


        $delivery = Delivery::find($id);


        $delivery->update([
            'rider_location_id' => $riderLocation->id,
            'status' => DeliveryStatus::ON_DELIVER->value
        ]);

        $order = Order::find($delivery->transaction->order->id);

        $order->update([
            'status' => DeliveryStatus::ON_DELIVER->value
        ]);


        $customer = User::find($order->user->id);

        $orderStatusMessage = [
            'order_id' => $order->order_num,
            'status' => $order->status,
            'message' => "Your order is on delivery"
        ];


        $customer->notify(new OrderStatus($orderStatusMessage));




        $deliveries = $this->delivery->where('user_id', $user->id)->with(['transaction.order.payment', 'location'])->get();

        return response(['message' => 'Delivery Accepted', 'deliveries' => $deliveries], 200);
    }
    public function updateLocation(string $id, Request $request) {


        $location = Location::find($id);


        $location->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);


        return response(['message' => 'location updated']);
    }

    public function completeCOD (string $id, Request $request) {


        $request->validate([
            'image' => 'required|sometimes|base64mimes:jpg,png,jpeg'
        ]);


        $delivery = Delivery::find($id);



        $delivery->update([
            'status' => DeliveryStatus::DONE->value
        ]);

        $payment = Payment::find($request->paymentId);

        $image = $request->image;  // your base64 encoded
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName =  'img' . now() . '.' . 'png';
        $filename = preg_replace('~[\\\\\s+/:*?"<>|+-]~', '-', $imageName);

        $imageDecoded = base64_decode($image);

        PaymentImage::create([
            'name' => $imageName,
            'url' => asset('storage/payment/image/' . $filename),
            'payment_id' => $payment->id
        ]);
        Storage::disk('public')->put('payment/image/' . $filename, $imageDecoded);

        $payment->update([
            'status' => PaymentStatusAndType::PAID->value
        ]);

        $order = $delivery->transaction->order;

        $order->update([
            'status' => 'done'
        ]);


        $owner = User::role('admin')->first();
        $employee = User::find($order->transaction->user->id);


        $orderStatusMessage = [
            'order_id' => $order->order_num,
            'status' => $order->status,
            'message' => "Your order is on Done"
        ];


        $owner->notify(new OrderStatus($orderStatusMessage));

        $employee->notify(new OrderStatus($orderStatusMessage));


        return response([
            'message' => 'Deliver Success'
        ], 200);
    }

    public function completeDelivery(string $id){

        $delivery = Delivery::find($id);



        $delivery->update(['status' => DeliveryStatus::DONE->value]);


        $order = $delivery->transaction->order;


        $order->update([
            'status' => 'done'
        ]);


        return response([
            'message' => 'Deliver Success'
        ], 200);

    }
    public function riderLocation($id){

        $location = Location::where('id', $id)->with(['user'])->first();



        return response($location, 200);
    }
}
