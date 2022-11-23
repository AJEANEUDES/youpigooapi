<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        User::truncate();

        $users = [
            [

                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => '12345678',
                'is_admin' => 1,
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => '13456',
                'is_admin' => 0,
            ],
            [
                'name' => 'Client',
                'email' => 'client@gmail.com',
                'password' => '13456',
                'is_admin' => 0,
            ],

            [
                'name' => 'Hotel',
                'email' => 'hotel@gmail.com',
                'password' => '13456',
                'is_admin' => 0,
            ],

            [
                'name' => 'compagnie',
                'email' => 'compagnie@gmail.com',
                'password' => '13456',
                'is_admin' => 0,
            ],
        ];


        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password'])
            ]);

            $role = Role::create(['name' => 'SuperAdmin']);

            $permissions = Permission::pluck('id', 'id')->all();

            $role->syncPermissions($permissions);

            $user->assignRole([$role->id]);
            
        }
    }
}
