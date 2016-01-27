<?php
use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;
class UserSeeder extends Seeder {

    public function run() {

        DB::table('users')->delete();
        $user = User::create(array(
            'nombre' => 'Admin',
            'apellido' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));
        $role = Role::where('name', '=', 'admin')->get()->first();
        $user->attachRole( $role );

//        $user = User::create(array(
//            'nombre' => 'Pepe',
//            'apellido' => 'Lopez',
//            'email' => 'pepe@mail.com',
//            'password' => Hash::make('123456'),
//            'created_at' => new DateTime,
//            'updated_at' => new DateTime
//        ));
//        $role = Role::where('name', '=', 'participante')->get()->first();
//        $user->attachRole( $role );
//
//        $user = User::create(array(
//            'nombre' => 'Lola',
//            'apellido' => 'Flores',
//            'email' => 'lola@mail.com',
//            'password' => Hash::make('123456'),
//            'created_at' => new DateTime,
//            'updated_at' => new DateTime
//        ));
//        $role = Role::where('name', '=', 'profesor')->get()->first();
//        $user->attachRole( $role );
//
//        $user = User::create(array(
//            'nombre' => 'Luis',
//            'apellido' => 'Gonzalez',
//            'email' => 'luis@mail.com',
//            'password' => Hash::make('123456'),
//            'created_at' => new DateTime,
//            'updated_at' => new DateTime
//        ));
//        $role = Role::where('name', '=', 'coordinador')->get()->first();
//        $user->attachRole( $role );

    }
}
//public function run()
//{
//    DB::table('users')->delete();
//    $user = User::create(array(
//        'nombre' => 'Admin',
//        'apellido' => 'Administrador',
//        'documento_identidad' => '15896328',
//        'telefono' => '02125556699',
//        'email' => 'admin@admin.com',
//        'password' => Hash::make('123456'),
//        'created_at' => new DateTime,
//        'updated_at' => new DateTime
//    ));
//    $role = Role::where('name', '=', 'admin')->get()->first();
//    $user->attachRole( $role );
//}

//public function run()
//{
//    DB::table('users')->delete();
//    $user = User::create(array(
//        'nombre' => 'Admin',
//        'apellido' => 'Administrador',
//        'documento_identidad' => '15896328',
//        'telefono' => '02125556699',
//        'email' => 'admin@admin.com',
//        'password' => Hash::make('123456'),
//        'created_at' => new DateTime,
//        'updated_at' => new DateTime
//    ));
//    $role = Role::where('name', '=', 'admin')->get()->first();
//    $user->attachRole( $role );
//}
