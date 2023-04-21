<?php 

namespace App\Actions\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class StoreRoleAction{
    public function handle(Request $request){
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return $role;
    }
}