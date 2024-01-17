<?php

namespace App\Actions\Supply;

use App\Models\Supply;
use Illuminate\Http\Request;

class StoreSupplyAction {
    public function handle (Request $request) : void {

        foreach($request->supplies as $supply) {
           $supplyData = Supply::create([
                'name' => $supply['name'],
                'quantity' => $supply['quantity'],
                'amount' => $supply['amount'],
                'unit' => $supply['unit'],
                'category'=> $supply['category'],
                'expiry_date' => $supply['expiry_date'],
                'manufacturer' => $supply['manufacturer']
            ]);

            if($supply['category'] === 'Add On'){
                $supplyData->update([
                    'price' => $supply['price']
                ]);
            }
        }
    }
}
