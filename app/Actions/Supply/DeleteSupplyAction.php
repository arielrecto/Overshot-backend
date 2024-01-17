<?php


namespace App\Actions\Supply;

use App\Models\Supply;

class DeleteSupplyAction {

    public function handle($id) {

        $supply = Supply::find($id)->delete();

        if(!$supply) {
            return abort(401);
        }

        $supplies = Supply::get();

        return response(['message' => 'Item Successfully Deleted', 'supplies' => $supplies], 200);
    }
}
