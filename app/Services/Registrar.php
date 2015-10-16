<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'nombre' => 'required|max:255',
			'apellido' => 'required|max:255',
			'di' => 'required|max:50',
			'telefono' => 'required|max:20	',
			'email' => 'required|email|max:255|unique:users',
			'clave' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'nombre' => $data['nombre'],
			'apellido' => $data['apellido'],
			'documento_de_identidad' => $data['di'],
			'telefono' => $data['telefono'],
			'email' => $data['email'],
			'clave' => bcrypt($data['clave']),
		]);
	}

}
