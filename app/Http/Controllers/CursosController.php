<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\CursoRequest;
use App\Models\ModalidadCurso;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use DateTime;

use App\Models\Curso;
use App\Models\ModalidadPago;
use App\Models\TipoCurso;
use Illuminate\Http\Request;

class CursosController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		try{
			$data['cursos'] = Curso::all();
            foreach($data['cursos'] as $curso){
//                dd($curso->id_tipo);
                $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                dd($tipo->nombre);
                $curso['tipo_curso'] = $tipo->nombre;
            }

            return view('cursos.cursos', $data);
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
		try{

            Session::forget('nombre');
            Session::forget('id_tipo');
            Session::forget('fecha_inicio');
            Session::forget('fecha_fin');
            Session::forget('duracion');
            Session::forget('lugar');
            Session::forget('descripcion');
            Session::forget('dirigido_a');
            Session::forget('proposito');
            Session::forget('modalidad_estrategias');
            Session::forget('acreditacion');
            Session::forget('perfil');
            Session::forget('requerimientos_tec');
            Session::forget('perfil_egresado');
            Session::forget('instituciones_aval');
            Session::forget('aliados');
            Session::forget('plan_estudio');
            Session::forget('costo');
            Session::forget('descripcion_carrusel');


            $data['tipos'] = TipoCurso::all()->lists('nombre','id');
			$data['modalidad_pago'] = ModalidadPago::all()->lists('nombre','id');
            $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre','id');
            $data['errores'] = '';
			return view ('cursos.crear', $data);
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CursoRequest $request)
	{
		try
		{
//            Session::forget('nombre');
//            Session::forget('id_tipo');
//            Session::forget('fecha_inicio');
//            Session::forget('fecha_fin');
//            Session::forget('duracion');
//            Session::forget('lugar');
//            Session::forget('descripcion');
//            Session::forget('dirigido_a');
//            Session::forget('proposito');
//            Session::forget('modalidad_estrategias');
//            Session::forget('acreditacion');
//            Session::forget('perfil');
//            Session::forget('requerimientos_tec');
//            Session::forget('perfil_egresado');
//            Session::forget('instituciones_aval');
//            Session::forget('aliados');
//            Session::forget('plan_estudio');
//            Session::forget('costo');
//            Session::forget('descripcion_carrusel');

            $data['errores'] = '';
            $modalidaes = Input::get('modalidades_pago');
//            dd($modalidaes);
			if ( empty(Input::get( 'modalidades_pago' )) ) {
//                    dd("fallo modalidad");
				$data['errores'] = "Debe seleccionar una modalidad de pago";
				$data['tipos'] = TipoCurso::all()->lists('nombre','id');
				$data['modalidad_pago'] = ModalidadPago::all()->lists('nombre','id');
                $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre','id');

                Session::set('nombre', $request->nombre);
                Session::set('id_tipo', $request->id_tipo);
                Session::set('fecha_inicio', $request->fecha_inicio);
                Session::set('fecha_fin', $request->fecha_fin);
                Session::set('duracion', $request->duracion);
                Session::set('lugar', $request->lugar);
                Session::set('descripcion', $request->descripcion);
                Session::set('dirigido_a', $request->dirigido_a);
                Session::set('proposito', $request->proposito);
                Session::set('modalidad_estrategias', $request->modalidad_estrategias);
                Session::set('acreditacion', $request->acreditacion);
                Session::set('perfil', $request->perfil);
                Session::set('requerimientos_tec', $request->requerimientos_tec);
                Session::set('perfil_egresado', $request->perfil_egresado);
                Session::set('instituciones_aval', $request->instituciones_aval);
                Session::set('aliados', $request->aliados);
                Session::set('plan_estudio', $request->plan_estudio);
                Session::set('costo', $request->costo);
                Session::set('descripcion_carrusel', $request->descripcion_carrusel);

				return view('cursos.crear', $data);

			}else{

//                $fecha_inicio = new DateTime($request->fecha_inicio);
//                $fecha_fin = new DateTime($request->fecha_fin);

				$create2 = Curso::findOrNew($request->id);
				$create2->id_tipo = $request->id_tipo;
				$create2->nombre = $request->nombre;
				$create2->fecha_inicio = $request->fecha_inicio;
				$create2->fecha_fin = $request->fecha_fin;
//                $interval = $fecha_inicio->diff($fecha_fin);
				$create2->duracion = $request->duracion;
                $create2->id_modalidad_curso = $request->id_modalidad_curso;
				$create2->lugar = $request->lugar;
				$create2->area ='';
				$create2->descripcion = $request->descripcion;
				$create2->dirigido_a = $request->dirigido_a;
				$create2->propositos = $request->proposito;
				$create2->modalidad_estrategias = $request->modalidad_estrategias;
				$create2->acreditacion = $request->acreditacion;
				$create2->perfil = $request->perfil;
				$create2->requerimientos_tec = $request->requerimientos_tec;
				$create2->perfil_egresado = $request->perfil_egresado;
				$create2->instituciones_aval = $request->instituciones_aval;
				$create2->aliados = $request->aliados;
				$create2->plan_estudio = $request->plan_estudio;
				$create2->id_modalidad_pago = '1';
				$create2->modalidades_pago = '';
				$create2->costo = $request->costo;
				$create2->imagen_carrusel = '';
				$create2->descripcion_carrusel = $request->descripcion_carrusel;
				$create2->activo_carrusel = $request->activo_carrusel;
			}




			if($create2->save()) {
				return redirect('/cursos');
			}else{
				Session::set('error','Ha ocurrido un error inesperado');
				return view('cursos.crear');
			}

		}
		catch (Exception $e)
		{
			return view('errors.error')->with('error',$e->getMessage());
		}
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
		try{
//            dd($id );
			$data['cursos'] = Curso::find($id);
			$data['tipo'] = $data['cursos']->id_tipo;
            $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre','id');
            $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre','id');
			$data['tipos'] = TipoCurso::all()->lists('nombre','id');

			return view ('cursos.editar', $data);
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
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
