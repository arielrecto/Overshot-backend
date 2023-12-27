<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function __construct(public Rating $rating)
    {

    }

    public function product(Request $request, $id){


        $product = Product::find($id);

        Rating::create([
            'rate' => $request->rate,
            'message' => $request->message,
            'product_id' => $product->id
        ]);

        return response(['message' => 'sent Success'], 200);
    }
}
