<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

//		Eloquent::unguard();

		$this->call('PermissionsSeeder');
		$this->call('RolesSeeder');
		$this->call('UserSeeder');
		$this->command->info('Datos insertados!');

//		 $this->call('UserTableSeeder');
	
//		$this->call("VaultTableSeeder");
	}

}