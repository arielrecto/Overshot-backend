<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function products()
    {

        $categories = Category::get();

        $products = Product::with(['categories', 'image', 'ratings' , 'promo.promo' => function($q){
            $q->where('is_active', true);
        }])->withAvg('ratings', 'rate')->get();


        return response([
            'categories' => $categories,
            'products' => $products
        ]);
    }
    public function productShow($id){

        $product = Product::where('id', $id)->with(['image', 'ratings','categories', 'promo.promo' => function($q){
            $q->where('is_active', true);
        }])->withAvg('ratings', 'rate')->first();


        return response($product, 200);

    }
}
