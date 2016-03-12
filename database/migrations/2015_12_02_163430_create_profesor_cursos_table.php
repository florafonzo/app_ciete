<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfesorCursosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profesor_cursos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_profesor')->unsigned();
			$table->integer('id_curso')->unsigned();
			$table->string('seccion');
			$table->timestamps();


			$table->foreign('id_profesor')->references('id')->on('profesores')
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
		Schema::drop('profesor_cursos');
	}

}
