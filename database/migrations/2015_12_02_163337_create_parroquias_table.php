<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParroquiasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parroquias', function(Blueprint $table)
		{
			$table->increments('id_parroquia');
			$table->integer('id_municipio')->unsigned();
			$table->string('parroquia');
			$table->nullableTimestamps();


			$table->foreign('id_municipio')->references('id_municipio')->on('municipios')
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
		Schema::drop('parroquias');
	}

}
