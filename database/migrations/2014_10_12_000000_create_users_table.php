<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre');
			$table->string('apellido');
//			$table->string('documento_identidad')->unique();
//			$table->string('telefono');
			$table->string('email')->unique();
			$table->string('foto');
			$table->string('password', 60);
			$table->rememberToken();
			$table->timestamps();
//            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
