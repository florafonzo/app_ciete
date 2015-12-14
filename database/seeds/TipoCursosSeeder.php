<?php

use Illuminate\Database\Seeder;
use App\Models\TipoCurso;
use Illuminate\Support\Facades\DB;

class TipoCursoSeeder extends Seeder {

    public function run() {

        DB::table('tipo_cursos')->delete();

        TipoCurso::create(array(
            'nombre' => 'Diplomado',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

        TipoCurso::create(array(
            'nombre' => 'Cápsula',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

    }

}
