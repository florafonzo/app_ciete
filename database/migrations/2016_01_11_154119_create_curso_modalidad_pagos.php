<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCursoModalidadPagos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('curso_modalidad_pagos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_curso')->unsigned();
			$table->integer('id_modalidad_pago')->unsigned();
			$table->timestamps();


			$table->foreign('id_curso')->references('id')->on('cursos')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_modalidad_pago')->references('id')->on('modalidad_pagos')
				->onUpdate('cascade')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('curso_modalidad_pagos');
	}

}
