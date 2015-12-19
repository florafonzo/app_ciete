<?php

use Illuminate\Database\Seeder;
use App\Models\ModalidadPago;
use Illuminate\Support\Facades\DB;

class ModalidadPagosSeeder extends Seeder {

    public function run() {

        DB::table('modalidad_pagos')->delete();

        ModalidadPago::create(array(
            'nombre' => 'Transferencia',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

        ModalidadPago::create(array(
            'nombre' => 'Depósito',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

        ModalidadPago::create(array(
            'nombre' => 'Paypal',
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ));

    }

}
