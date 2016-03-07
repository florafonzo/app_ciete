<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipanteCursosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('participante_cursos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_participante')->unsigned();
			$table->integer('id_curso')->unsigned();
			$table->string('seccion');
			$table->timestamps();


			$table->foreign('id_participante')->references('id')->on('participantes')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_curso')->references('id')->on('cursos')
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
		Schema::drop('participante_cursos');
	}

}
