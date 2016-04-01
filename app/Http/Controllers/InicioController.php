<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Webinar;
use Illuminate\Support\Facades\Auth;
use DateTime;

use Illuminate\Http\Request;
use League\Flysystem\Exception;

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
			$data['webinars'] = Webinar::where('activo_carrusel','=', true)
										->where('webinar_activo', '=', true)->get();

			return view('inicio', $data);
		}
		catch (Exception $e) {

				return view('errors.error')->with('error',$e->getMessage());
		}
	}

	public function descCurso($id){
		try{
            $data['errores'] = '';
            $usuario_actual = Auth::user();

            if($usuario_actual != null) {
                if ($usuario_actual->foto != null) {
                    $data['foto'] = $usuario_actual->foto;
                } else {
                    $data['foto'] = 'foto_participante.png';
                }
            }

            $data['curso'] = Curso::find($id);
            $data['inicio'] = new DateTime($data['curso']->fecha_inicio);
            $data['fin'] = new DateTime($data['curso']->fecha_fin);
            return view('descripcion-curso', $data);

		}catch (Exception $e){
			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	public function descWebinar($id){
		try{
            $data['errores'] = '';
            $usuario_actual = Auth::user();

            if($usuario_actual != null) {
                if ($usuario_actual->foto != null) {
                    $data['foto'] = $usuario_actual->foto;
                } else {
                    $data['foto'] = 'foto_participante.png';
                }
            }

            $data['webinar'] = Webinar::find($id);
            $data['inicio'] = new DateTime($data['webinar']->fecha_inicio);
            $data['fin'] = new DateTime($data['webinar']->fecha_fin);
            return view('descripcion-webinar', $data);

		}catch (Exception $e){
			return view('errors.error')->with('error',$e->getMessage());
		}
	}

}
