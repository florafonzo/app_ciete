<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfesorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profesores', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_usuario')->unsigned();
			$table->string('nombre');
			$table->string('apellido');
			$table->string('documento_identidad')->unique();
			//$table->string('foto');
			$table->string('telefono');
			$table->string('celular');
			$table->timestamps();


			$table->foreign('id_usuario')->references('id')->on('users')
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
		Schema::drop('profesores');
	}

}
