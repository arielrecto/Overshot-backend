<?php 


namespace App\Actions\Supply;

use App\Models\Supply;

class DeleteSupplyAction {
 
    public function handle($id) {

        $supply = Supply::find($id)->delete();

        if(!$supply) {
            return abort(401);
        }

        return response(['message' => 'Item Successfully Deleted'], 200);
    }
}