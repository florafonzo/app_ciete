<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;
use App\Models\Profesor;
use Illuminate\Support\Facades\DB;
//use database\seeds\DateTime;

class ProfesoresSeeder extends Seeder {

    public function run() {

        DB::table('profesores')->delete();

        $user = User::where('email', '=', 'admin@admin.com')->get()->first();
        $profesor = Profesor::create(array(
            'id_usuario' => $user->id,
            'nombre' => 'Admin',
            'apellido' => 'Administrador',
            'documento_identidad' => '5222688',
            'foto' => '',
            'telefono' => '02125558899',
            'celular' => '04168559966',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

//        $user->attachRole( $role );

    }

}
