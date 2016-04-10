<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use App\Models\Preinscripcion;
use Illuminate\Http\Request;

class InscripcionController extends Controller {


	public function indexCurso() {
		try{

			//Verificación de los permisos del usuario para poder realizar esta acción
			$usuario_actual = Auth::user();
			if($usuario_actual->foto != null) {
				$data['foto'] = $usuario_actual->foto;
			}else{
				$data['foto'] = 'foto_participante.png';
			}

			if($usuario_actual->can('activar_inscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
				$data['errores'] = '';
				$data['busq_'] = false;
				$data['usuarios'] = Preinscripcion::all();
				$data['tipos'] = ['curso', 'webinar'];

				return view('inscripciones.inscripciones', $data);

			}else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

				return view('errors.sin_permiso');
			}
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}


	public function buscarInscripcion()
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
