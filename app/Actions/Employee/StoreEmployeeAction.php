<?php

namespace App\Actions\Employee;

use App\Actions\Role\AssignRoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreEmployeeAction
{
    public function handle(Request $request)
    {
        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        (new AssignRoleUser())->handle($employee, 'employee', 'web');

        return $employee;
    }
}
