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

        $products = Product::with('categories', 'image')->get();


        return response([
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
