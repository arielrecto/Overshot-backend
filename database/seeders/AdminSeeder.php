<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\AdminFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $user = User::create([
        'name' => 'ariel recto',
        'email' => 'arielrecto@gmail.com',
        'password' => Hash::make('ariel123')
       ]);

       $role = Role::create(['name' => 'admin']);

       $user->assignRole($role);
    }
}
