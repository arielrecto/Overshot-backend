<?php

namespace App\Actions\Employee;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supply;

class GetItemsDataAction {


    public function handle () {

        $products = Product::with('image', 'categories', 'sizes')->get();
        $supplies = Supply::all();
        $categories = Category::get();


        return [
            'products' => $products,
            'supplies' => $supplies,
            'categories' => $categories
        ];
    }

}
