<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Database\Factories\AdminFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            'slug_name' => Str::slug('ariel recto'),
            'password' => Hash::make('ariel123')
        ]);

        $role = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);
        $employeeRole =  Role::create(['name' => 'employee']);
        $riderRole =  Role::create(['name' => 'rider']);

        $employee = User::create([
            'name' => 'ariel recto',
            'email' => 'arielrecto2@gmail.com',
            'slug_name' => Str::slug('ariel recto'),
            'password' => Hash::make('ariel123')
        ]);

        $client = User::create([
            'name' => 'ariel recto',
            'email' => 'arielrecto1@gmail.com',
            'slug_name' => Str::slug('ariel recto'),
            'password' => Hash::make('ariel123')
        ]);

        $milktea = Category::create([
            'name' => 'milk tea',
        ]);
        $drink = Category::create([
            'name' => 'drink',
        ]);

        // $rider = User::create([
        //     'name' => 'ariel recto',
        //     'email' => 'arielrectoRider@gmail.com',
        //     'slug_name' => Str::slug('ariel recto'),
        //     'password' => Hash::make('ariel123')
        // ]);



        // $rider->assignRole($riderRole);

        $employee->assignRole($employeeRole);
        $client->assignRole($clientRole);
        $user->assignRole($role);
    }
}
