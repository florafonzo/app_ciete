<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Curso;
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
			$data['tipos'] = TipoCurso::all()->lists('nombre','id');

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
			$create2 = Curso::findOrNew($request->id);
			$create2->id_tipo = $request->id_tipo;
			$create2->nombre = $request->nombre;
			$create2->fecha = $request->fecha;
			$create2->lugar = $request->lugar;
			$create2->descripcion->descripcion;
			$create2->dirigiso_a = $request->dirigiso_a;
			$create2->proposito = $request->proposito;
			$create2->modalidad_estrategias = $request->modalidad_estrategias;
			$create2->acreditacion = $request->acreditacion;
			$create2->perfil = $request->perfil;
			$create2->requerimientos_tec = $request->requerimientos_tec;
			$create2->perfil_egresado = $request->perfil_egresado;
			$create2->instituciones_aval = $request->instituciones_aval;
			$create2->aliados = $request->aliados;
			$create2->plan_estudio = $request->plan_estudio;
			$create2->costo = $request->costo;
			$create2->imagen_carrusel = $request->imagen_carrusel;
			$create2->descripcion_carrusel = $request->descripcion_carrusel;
			$create2->activo_carrusel = $request->activo_carrusel;


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
