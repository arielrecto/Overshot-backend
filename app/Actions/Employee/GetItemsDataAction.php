<?php

namespace App\Actions\Employee;

use App\Models\Product;
use App\Models\Supply;

class GetItemsDataAction {


    public function handle () {

        $products = Product::with('image')->get();
        $supplies = Supply::all();


        return [
            'products' => $products,
            'supplies' => $supplies
        ];
    }

}
