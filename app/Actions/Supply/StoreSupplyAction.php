<?php 

namespace App\Actions\Supply;

use App\Models\Supply;
use Illuminate\Http\Request;

class StoreSupplyAction {
    public function handle (Request $request){
        $supply = Supply::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'unit' => $request->unit
        ]);

        return $supply;
    }
}