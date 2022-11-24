<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('model_has_roles')->truncate();
        
        $utilisateur = User::create([
                'name' => 'utilisateur',
                'email' => 'utilisateur@utilisateur.com',
                'password' => Hash::make('13456') //password= "13456" pour les tests
        ]);
         


        // foreach ($users as $user) {
        //     $utilisateur = User::create([
        //         'name' => $user['name'],
        //         'email' => $user['email'],
        //         'password' => Hash::make($user['password'])
        //     ]);

            // $role = Role::create(['name' => 'SuperAdmin']);

            // $permissions = Permission::pluck('id', 'id')->all();

            // $role->syncPermissions($permissions);

            $utilisateurRole = Role::where('name', 'utilisateur')->first(); 

            $utilisateur->roles()->attach($utilisateurRole);
            
    }
}
