<?php

namespace App\Http\Controllers;

use App\Actions\Role\StoreRoleAction;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function store(StoreRoleRequest $request , StoreRoleAction $storeRoleAction){
        $role = $storeRoleAction->handle($request);

        return Response($role, '200');
    }
}
