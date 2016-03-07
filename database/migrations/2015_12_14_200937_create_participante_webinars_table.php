<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipanteWebinarsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('participante_webinars', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('id_participante')->unsigned();
			$table->integer('id_webinar')->unsigned();
			$table->string('seccion');
			$table->timestamps();


			$table->foreign('id_participante')->references('id')->on('participantes')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_webinar')->references('id')->on('webinars')
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
		Schema::drop('participante_webinars');
	}

}
