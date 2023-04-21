<?php

namespace App\Actions\Role;

use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleUser
{

    public function handle(User $user, $name, $guard_name)
    {

        if(Role::where('name', '=' ,$name)->get()->count() == 0){
            return null;
        }
        $role = Role::findByName($name, 'web');

        $user->assignRole($role);

        return $user;
    }
}
