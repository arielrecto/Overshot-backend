<?php


namespace App\Actions\Dashboard\Admin;

use App\Models\User;


class Overview {


    public function handle(){
        $totalUser = User::role('user')->get();



        return [
            'total' => [
                'users' => $totalUser
            ]
        ];
    }
}
