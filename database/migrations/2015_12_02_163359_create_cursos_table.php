<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCursosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cursos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_tipo')->unsigned();
			$table->string('nombre')->unique();
			$table->date('fecha');
			$table->text('lugar');
			$table->text('descripcion');
			$table->string('area');
			$table->text('dirigido_a');
			$table->text('propositos');
			$table->text('modalidad_estrategias');
			$table->text('acreditacion');
			$table->text('perfil');
			$table->text('requerimientos_tec');
			$table->text('perfil_egresado');
			$table->text('instituciones_aval');
			$table->text('aliados');
			$table->text('plan_estudio');
			$table->float('costo');
			$table->text('modalidades_pago');
			$table->string('imagen_carrusel');
			$table->text('descripcion_carrusel');
			$table->boolean('activo_carrusel');
			$table->timestamps();

			$table->foreign('id_tipo')->references('id')->on('tipo_cursos')
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
		Schema::drop('cursos');
	}

}
