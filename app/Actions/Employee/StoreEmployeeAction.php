<?php

namespace App\Actions\Employee;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Actions\Role\AssignRoleUser;
use Illuminate\Support\Facades\Hash;

class StoreEmployeeAction
{
    public function handle(Request $request)
    {
        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'slug_name' => Str::slug($request->name),
            'password' => Hash::make($request->password)
        ]);
        (new AssignRoleUser())->handle($employee, 'employee', 'web');

        return $employee;
    }
}
