<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class CreateSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::truncate();

        $user = User::create([

            // 'nom' => 'Superadmin',
            // 'nom' => 'Superadmin',
            // 'email' => 'superadmin@gmail.com',
            // 'password' => bcrypt('123456'),
            'nom_user'=> 'E',
            'prenoms_user' => 'Media',
            'email_user' => 'superadmin@gmail.com',
            'prefix_user' => '00228',
            'telephone_user' => '90909899',
            'adresse_user' => 'Agbalépédo, Adidohadin',
            'pays_user' => 'TOGO',
            'ville_user' => 'Lomé',
            'roles_user' => 'SuperAdmin',
            'password'=> bcrypt('12345678'),
            'confirmation_password' => bcrypt('12345678')

        ]);

        // $role = Role::create(['name' => 'SuperAdmin']);

        // $permissions = Permission::pluck('id','id')->all();

        // $role->syncPermissions($permissions);

        // $user->assignRole([$role->id]);


    }
}
