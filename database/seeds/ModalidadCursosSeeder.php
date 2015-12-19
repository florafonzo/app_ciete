<?php

use Illuminate\Database\Seeder;
use App\Models\ModalidadCurso;
use Illuminate\Support\Facades\DB;

class ModalidadCursosSeeder extends Seeder {

    public function run() {

        DB::table('modalidad_cursos')->delete();

        ModalidadCurso::create(array(
            'nombre' => 'Presencial',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

        ModalidadCurso::create(array(
            'nombre' => 'Virtual',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

    }

}
