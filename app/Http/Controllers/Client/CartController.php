<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {


        $user = Auth::user();

        $cart = $user->cart->where('is_check_out', false)->first();
        if ($cart === null) {
            $cart = Cart::create([
                'ref' => 'CRT-' . Str::slug(now()),
                'quantity' => $request->customData['quantity'],
                'total' => $request->price,
                'user_id' => $user->id
            ]);
        }

        $product = Product::find($request->id);

        CartProduct::create([
            'product_id' => $product->id,
            'cart_id' => $cart->id,
            'quantity' => $request->customData['quantity'],
            'price' => $request->price,
            'sugar_level' => $request->levels[0]['pivot']['percent'],
            'size' => json_encode($request->customData['size']),
            'addons' =>  json_encode($request->customData['addons']),
        ]);


        $c_products = $cart->cartProducts;
        $totalQuantity = 0;
        $totalPrice = 0;
        foreach($c_products as $c_product){
            $totalQuantity += $c_product->quantity;
            $totalPrice += $c_product->price;
        }

        $cart->update([
            'quantity' => $totalQuantity,
            'total' => $totalPrice
        ]);

        $cart = Cart::with(['cartProducts' => function($c_product){
            $c_product->with(['product' => function($product){
                $product->with(['image', 'categories']);
            }]);
        }])->where('user_id', $user->id)->where('is_check_out', false)->first();

        return response([
            'cart' => $cart,
        ], 200);
    }
    public function index(){
        $user = Auth::user();


        $cart = Cart::with(['cartProducts' => function($c_product){
            $c_product->with(['product' => function($product){
                $product->with(['image', 'categories']);
            }]);
        }])->where('user_id', $user->id)->where('is_check_out', false)->first();


        return response([
            'cart' => $cart
        ], 200);
    }
}
