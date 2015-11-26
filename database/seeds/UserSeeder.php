<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;

class UserSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();
        $user = User::create(array(
            'nombre' => 'Admin',
            'apellido' => 'Administrador',
            'documento_identidad' => '15896328',
            'telefono' => '02125556699',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));
        $role = Role::where('name', '=', 'admin')->get()->first();
        $user->attachRole( $role );
    }

}