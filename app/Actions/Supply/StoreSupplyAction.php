<?php

namespace App\Actions\Supply;

use App\Models\Supply;
use Illuminate\Http\Request;

class StoreSupplyAction {
    public function handle (Request $request) : void {

        foreach($request->supplies as $supply) {
            Supply::create([
                'name' => $supply['name'],
                'quantity' => $supply['quantity'],
                'amount' => $supply['amount'],
                'unit' => $supply['unit'],
                'category'=> $supply['category']
            ]);
        }
    }
}
