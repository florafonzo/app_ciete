<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use DB;
use DateTime;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\Profesor;
use App\User;
use Illuminate\Http\Request;

class ProfesoresController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		try {
			//Verificación de los permisos del usuario para poder realizar esta acción
			$usuario_actual = Auth::user();
			if($usuario_actual->foto != null) {
				$data['foto'] = $usuario_actual->foto;
			}else{
				$data['foto'] = 'foto_participante.png';
			}

			if($usuario_actual->can('ver_perfil_prof')) { // Si el usuario posee los permisos necesarios continua con la acción
				$data['errores'] = '';
				return view('inicio', $data);

			}else{      // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

				return view('errors.sin_permiso');
			}

		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Muestra los datos de perfil de profesor
	 *
	 * @return Vista con los datos
	 */
	public function verPerfil()
	{
		try{
			//Verificación de los permisos del usuario para poder realizar esta acción
			$usuario_actual = Auth::user();
			if($usuario_actual->foto != null) {
				$data['foto'] = $usuario_actual->foto;
			}else{
				$data['foto'] = 'foto_participante.png';
			}

			if($usuario_actual->can('ver_perfil_prof')) {     // Si el usuario posee los permisos necesarios continua con la acción

				$data['errores'] = '';
				$data['datos'] = Profesor::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del profesor
				$data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del profesor;
//                dd($data['datos']);

				return view('profesores.ver-perfil', $data);

			}else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

				return view('errors.sin_permiso');
			}
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
