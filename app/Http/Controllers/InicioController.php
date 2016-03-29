<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Webinar;
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

			$curso = new curso;
			$data['diplomados'] = $curso->getDiplos();
			$data['capsulas'] = $curso->getCaps();
			$data['webinars'] = Webinar::where('activo_carrusel','=', true)->get();

			return view('inicio', $data);
		}
		catch (Exception $e) {

				return view('errors.error')->with('error',$e->getMessage());
		}
	}

}
