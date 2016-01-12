<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\CursoRequest;
use App\Models\CursoModalidadPago;
use App\Models\ModalidadCurso;
use App\Models\Permission;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use DateTime;
use Exception;

use App\Models\Curso;
use App\Models\ModalidadPago;
use App\Models\TipoCurso;
use Illuminate\Http\Request;

class CursosController extends Controller {

	/**
	 * Muestra la vista de la lista de cursos si posee los permisos necesarios.
	 *
	 * @return Retorna la vista de la lista de cursos.
	 */
	public function index()
	{
		try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $permisos = [];
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'ver_lista_cursos'){
                    $si_puede = true;
                }
            }

            if($si_puede) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['cursos'] = Curso::all();
                foreach ($data['cursos'] as $curso) {
                    //                dd($curso->id_tipo);
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    //                $tipo = DB::table('tipo_cursos')->where('id', '=', $curso->id_tipo)->get();
                    //                dd($tipo[0]->nombre);
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                }

                return view('cursos.cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Muestra el formulario para crear un nuevo curso si posee los permisos necesarios.
	 *
	 * @return Retorna la vista del formulario vacío.
	 */
	public function create()
	{
		try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $permisos = [];
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'crear_cursos'){
                    $si_puede = true;
                }
            }

            if($si_puede) {

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


                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                $data['errores'] = '';

                return view('cursos.crear', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Guarda el nuevo curso con sus respectivos datos si el usuario posee los permisos necesarios.
     *
     * @param   CursoRequest    $request (Se validan los campos ingresados por el usuario antes guardarlos mediante el Request)
	 *
	 * @return Retorna la vista de la lista de cursos con el nuevo curso gregado.
	 */
	public function store(CursoRequest $request)
	{
		try
		{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $permisos = [];
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'crear_cursos'){
                    $si_puede = true;
                }
            }

            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
//                dd($request->hasFile('imagen_carrusel'));
                if ($request->hasFile('imagen_carrusel')) {
                    $imagen = $request->file('imagen_carrusel');
                } else {
                    $imagen = '';
                }

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

                $activo_carrusel = false;
                if (Input::get('activo_carrusel') == "on") {
                    //                dd("ON");
                    $activo_carrusel = true;
                } elseif (Input::get('activo_carrusel') == null) {
                    //                dd("Null");
                    $activo_carrusel = false;
                }

                if (empty(Input::get('modalidades_pago'))) {
                    //                    dd("fallo modalidad");
                    $data['errores'] = "Debe seleccionar una modalidad de pago.";
                    $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                    $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                    $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');


                    return view('cursos.crear', $data);

                }

                if (Input::get('activo_carrusel') == "on") {
                    if ((empty(Input::get('descripcion_carrusel'))) or !($request->hasFile('imagen_carrusel'))) {
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                        $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                        $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');

                        return view('cursos.crear', $data);
                    }
                }

                $modalidades = Input::get('modalidades_pago');
                //            dd($modalidades);

                $create2 = Curso::findOrNew($request->id);
                $create2->id_tipo = $request->id_tipo;
                $create2->curso_activo = "true";
                $create2->nombre = $request->nombre;
                $create2->fecha_inicio = $request->fecha_inicio;
                $create2->fecha_fin = $request->fecha_fin;
                $create2->duracion = $request->duracion;
                $create2->id_modalidad_curso = $request->id_modalidad_curso;
                $create2->lugar = $request->lugar;
                $create2->area = '';
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
                //			$create2->id_modalidad_pago = '1';
                //			$create2->modalidades_pago = '';
                $create2->costo = $request->costo;
                $create2->imagen_carrusel = $imagen;
                $create2->descripcion_carrusel = $request->descripcion_carrusel;
                $create2->activo_carrusel = $activo_carrusel;


                if ($create2->save()) {
                    foreach ($modalidades as $modalidad) {
                        $pago = ModalidadPago::where('nombre', '=', $modalidad)->get();
                        CursoModalidadPago::create([
                            'id_curso' => $create2->id,
                            'id_modalidad_pago' => $pago[0]->id,
                        ]);
                    }
                    return redirect('/cursos');
                } else {
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return view('cursos.crear');
                }
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
		}
		catch (Exception $e)
		{
			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Se muestra el formulario de edicion de cursos si posee los permisos necesarios.
	 *
	 * @param  int  $id (id del curso que se desa editar)
     *
	 * @return Retorna vista del formulario para el editar el curso deseado.
	 */
	public function edit($id)
	{
		try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $permisos = [];
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'editar_cursos'){
                    $si_puede = true;
                }
            }
            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['cursos'] = Curso::find($id);
                $data['tipo'] = $data['cursos']->id_tipo;
                $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');

                return view('cursos.editar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Actualiza los datos del curso seleccionado si posee los permisos necesarios
	 *
	 * @param  int  $id (id del curso que se desa editar)
     * @param  CursoRequest  $request (Se validan los campos ingresados por el usuario antes guardarlos mediante el Request)
     *
	 * @return Retorna la lista de cursos con los datos actualizados.
	 */
	public function update(CursoRequest $request, $id)
	{
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            $permisos = [];
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }

            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'editar_cursos'){
                    $si_puede = true;
                }
            }
            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $cursos = Curso::find($id);

                if (empty(Input::get('modalidades_pago'))) {
                    //                    dd("fallo modalidad");
                    $data['cursos'] = Curso::find($id);
                    $data['errores'] = "Debe seleccionar una modalidad de pago.";
                    $data['tipo'] = $data['cursos']->id_tipo;
                    $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                    $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                    $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                    $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');

                    return view('cursos.editar', $data);

                }

                if (($request->activo_carrusel) == true) {
                    if ((empty(Input::get('descripcion_carrusel'))) or (empty(Input::get('imagen_carrusel')))) {
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        $data['cursos'] = Curso::find($id);
                        $data['tipo'] = $data['cursos']->id_tipo;
                        $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                        $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                        $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                        $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');

                        return view('cursos.crear', $data);
                    }
                }

                if ($request->hasFile('imagen_carrusel')) {
                    $imagen = $request->file('imagen_carrusel');
                } else {
                    $imagen = $cursos->imagen_carrusel;
                }

                $cursos->id_tipo = $request->id_tipo;
                $cursos->nombre = $request->nombre;
                $cursos->fecha_inicio = $request->fecha_inicio;
                $cursos->fecha_fin = $request->fecha_fin;
                $cursos->duracion = $request->duracion;
                $cursos->id_modalidad_curso = $request->id_modalidad_curso;
                $cursos->lugar = $request->lugar;
                $cursos->area = '';
                $cursos->descripcion = $request->descripcion;
                $cursos->dirigido_a = $request->dirigido_a;
                $cursos->propositos = $request->proposito;
                $cursos->modalidad_estrategias = $request->modalidad_estrategias;
                $cursos->acreditacion = $request->acreditacion;
                $cursos->perfil = $request->perfil;
                $cursos->requerimientos_tec = $request->requerimientos_tec;
                $cursos->perfil_egresado = $request->perfil_egresado;
                $cursos->instituciones_aval = $request->instituciones_aval;
                $cursos->aliados = $request->aliados;
                $cursos->plan_estudio = $request->plan_estudio;
                $cursos->id_modalidad_pago = '1';
                $cursos->modalidades_pago = '';
                $cursos->costo = $request->costo;
                $cursos->imagen_carrusel = $imagen;
                $cursos->descripcion_carrusel = $request->descripcion_carrusel;
                $cursos->activo_carrusel = 'false';


                if ($cursos->save()) {
                    return redirect('/cursos');
                } else {
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return view('cursos.editar');
                }

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

	/**
	 * Desactiva el curso requerido.
	 *
	 * @param  int  $id
     *
	 * @return Retorna la vista de la lista de cursos actualizada.
	 */
	public function destroy($id)
	{
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            $permisos = [];
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'eliminar_cursos'){
                    $si_puede = true;
                }
            }

            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción

                $curso = Curso::find($id);
                //            Curso::destroy($id);
                $curso->curso_activo = false;
                $curso->save();

                $data['errores'] = '';
                $data['cursos'] = Curso::all();
                foreach ($data['cursos'] as $curso) {
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                }

                return view('cursos.cursos', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

}
