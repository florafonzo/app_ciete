<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class InicioController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		try {
			$data['errores'] = '';
			$usuario_actual = Auth::user();

			if($usuario_actual != null) {
				if ($usuario_actual->foto != null) {
					$data['foto'] = $usuario_actual->foto;
				} else {
					$data['foto'] = 'foto_participante.png';
				}
			}
			return view('inicio', $data);
		}
		catch (Exception $e) {

				return view('errors.error')->with('error',$e->getMessage());
		}
	}

}
