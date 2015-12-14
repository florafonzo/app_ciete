<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\seeds\PermisionsSeeder;
//use database\seeds\

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
		$this->call(PermisionsSeeder::class);
		$this->call(RolesSeeder::class);
		$this->call(UserSeeder::class);
        $this->call(ProfesoresSeeder::class);
        $this->call(TipoCursoSeeder::class);
        $this->call(ModalidadPagosSeeder::class);
        $this->call(ModalidadCursosSeeder::class);
		$this->command->info('Datos insertados!');

//		 $this->call('UserTableSeeder');
	
//		$this->call("VaultTableSeeder");
	}

}