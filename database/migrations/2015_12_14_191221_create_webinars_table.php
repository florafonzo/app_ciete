<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebinarsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('webinars', function(Blueprint $table)
		{
			$table->increments('id');
            $table->boolean('webinar_activo');
			$table->integer('cupos');
			$table->string('nombre')->unique();
			$table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('duracion');
			$table->text('lugar');
			$table->text('descripcion');
			$table->text('link');
            $table->timestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('webinars');
	}

}
