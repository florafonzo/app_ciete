<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('participantes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_usuario')->unsigned();
			$table->string('nombre');
			$table->string('apellido');
			$table->string('documento_identidad')->unique();
			//$table->string('foto');
			$table->string('telefono');
			$table->string('direccion');
			$table->string('celular');
			$table->string('correo_alternativo');
			$table->string('twitter');
			$table->string('ocupacion');
			$table->string('titulo_pregrado');
			$table->string('universidad');
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
		Schema::drop('participantes');
	}

}
