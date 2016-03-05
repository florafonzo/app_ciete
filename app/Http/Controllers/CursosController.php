<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\CursoRequest;
use App\Http\Requests\CursoEditRequest;
use App\Http\Requests\CursoParticipanteRequest;

use App\Models\CursoModalidadPago;
use App\Models\ModalidadCurso;
use App\Models\Participante;
use App\Models\ParticipanteCurso;
use App\Models\Permission;

use App\Models\Profesor;
use App\Models\ProfesorCurso;
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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_lista_cursos')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_cursos')) {    // Si el usuario posee los permisos necesarios continua con la acción
//                $data['foto'] = $usuario_actual->foto;

                // Se eliminan los datos guardados en sesion anteriormente
                Session::forget('nombre');
                Session::forget('cupos');
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

                //Se obtienen todos los tipos de cursos, modalidades de pago y modalidades de curso.
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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';

                //  Se verifica si el usuario colocó una imagen en el formulario
                if ($request->hasFile('imagen_carrusel')) {
                    $imagen = $request->file('imagen_carrusel');
                } else {
                    $imagen = '';
                }

                // Se guardan los datos ingresados por el usuario en sesion pra utilizarlos en caso de que se redirija
                // al usuari al formulario por algún error y no se pierdan los datos ingresados
                Session::set('nombre', $request->nombre);
                Session::set('cupos', $request->cupos);
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

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del curso
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                    $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                    $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');

                    return view('cursos.crear', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                        $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                        $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');

                        return view('cursos.crear', $data);
                    }
                }

                $activo_carrusel = false;
                // Se verifica si el usuario elijió que el curso este activo en el carrusel o no
                if (Input::get('activo_carrusel') == "on") {
                    $activo_carrusel = true;
                } elseif (Input::get('activo_carrusel') == null) {
                    $activo_carrusel = false;
                }

                // Se verifica que el usuario haya seleccionado por lo menos una modalidad de pago
                if (empty(Input::get('modalidades_pago'))) {    // Si no ha seleccionado ningúna modalidad, se redirige al formulario
                    $data['errores'] = "Debe seleccionar una modalidad de pago.";
                    $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                    $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                    $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');


                    return view('cursos.crear', $data);

                }

                //Se verifica si el usuario seleccionó que el curso esté activo en el carrusel
                if ($activo_carrusel) {
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ((empty(Input::get('descripcion_carrusel'))) or !($request->hasFile('imagen_carrusel'))) {   // Si no están completos se
                                                                                                    // redirige al usuario indicandole el error
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                        $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                        $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');

                        return view('cursos.crear', $data);
                    }
                }

                $modalidades = Input::get('modalidades_pago');  // Se obtienen las modalidades de pago seleccionadas

                // Se crea el nuevo curso con los datos ingresados
                $create2 = Curso::findOrNew($request->id);
                $create2->id_tipo = $request->id_tipo;
                $create2->curso_activo = "true";
                $create2->cupos = $request->cupos;
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
                $create2->costo = $request->costo;
                $create2->imagen_carrusel = $imagen;
                $create2->descripcion_carrusel = $request->descripcion_carrusel;
                $create2->activo_carrusel = $activo_carrusel;

                // Se verifica que se haya creado el el curso de forma correcta
                if ($create2->save()) {
                    foreach ($modalidades as $modalidad) {      // Se asocian las modalidades de pago al curso
                        $pago = ModalidadPago::where('nombre', '=', $modalidad)->get();
                        CursoModalidadPago::create([
                            'id_curso' => $create2->id,
                            'id_modalidad_pago' => $pago[0]->id,
                        ]);
                    }
                    return redirect('/cursos');
                } else {    // Si el curso no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
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
	 * @param  int  $id (id del curso que se desea editar)
     *
	 * @return Retorna vista del formulario para el editar el curso deseado.
	 */
	public function edit($id)
	{
		try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_cursos')) {   // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['cursos'] = Curso::find($id); // Se obtiene la información del curso seleccionado
                //Se obtienen todos los tipos de cursos, modalidades de pago y modalidades de curso.
                $data['tipo'] = $data['cursos']->id_tipo;
                $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['inicio'] = new DateTime($data['cursos']->fecha_inicio);
                $data['fin'] = new DateTime($data['cursos']->fecha_fin);

                $arr = [];
                foreach ($data['modalidad_pago'] as $index => $mod) {
                    $arr[$index] = false;
                }

                $pagos = CursoModalidadPago::where('id_curso', '=', $id)->orderBy('id_modalidad_pago')->get();
                foreach ($pagos as $index => $pago) {
                    if($pago->id_modalidad_pago == ($index + 1)){
                        $arr[$index+1] = true;
                    }else{
                        $arr[$index+1] = false;
                    }
                }
                $data['pagos'] = $arr;

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
	 * @param  int  $id (id del curso que se desea editar)
     * @param  CursoRequest  $request (Se validan los campos ingresados por el usuario antes guardarlos mediante el Request)
     *
	 * @return Retorna la lista de cursos con los datos actualizados.
	 */
	public function update(CursoEditRequest $request, $id)
	{
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_cursos')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $cursos = Curso::find($id);


                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del curso
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    $data['cursos'] = Curso::find($id);
                    $data['tipo'] = $data['cursos']->id_tipo;
                    $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                    $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                    $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                    $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');

                    return view('cursos.editar', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        $data['cursos'] = Curso::find($id);
                        $data['tipo'] = $data['cursos']->id_tipo;
                        $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                        $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                        $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                        $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');

                        return view('cursos.editar', $data);
                    }
                }

                // Se verifica que el usuario haya seleccionado por lo menos una modalidad de pago
                if (empty(Input::get('modalidades_pago'))) {    // Si no ha seleccionado ningúna modalidad, se redirige al formulario
                    $data['cursos'] = Curso::find($id);
                    $data['errores'] = "Debe seleccionar una modalidad de pago.";
                    $data['tipo'] = $data['cursos']->id_tipo;
                    $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                    $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                    $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                    $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');

                    return view('cursos.editar', $data);

                }

                //Se verifica si el usuario seleccionó que el curso esté activo en el carrusel
                if (($request->activo_carrusel) == true) {
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ((empty(Input::get('descripcion_carrusel'))) or !($request->hasFile('imagen_carrusel'))) {// Si los campos no están completos se
                                                                                                     // redirige al usuario indicandole el error
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        $data['cursos'] = Curso::find($id);
                        $data['tipo'] = $data['cursos']->id_tipo;
                        $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                        $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                        $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                        $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');

                        return view('cursos.editar', $data);
                    }
                }

                //  Se verifica si el usuario colocó una imagen en el formulario
                if ($request->hasFile('imagen_carrusel')) {
                    $imagen = $request->file('imagen_carrusel');
                } else {
                    $imagen = $cursos->imagen_carrusel;
                }
                $modalidades = Input::get('modalidades_pago');  // Se obtienen las modalidades de pago seleccionadas

                // Se actualizan los datos del curso seleccionado
                $cursos->id_tipo = $request->id_tipo;
                $cursos->cupos = $request->cupos;
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
                $cursos->costo = $request->costo;
                $cursos->imagen_carrusel = $imagen;
                $cursos->descripcion_carrusel = $request->descripcion_carrusel;
                $cursos->activo_carrusel = 'false';

                // Se verifica que se haya creado el curso de forma correcta
                if ($cursos->save()) {
                    DB::table('curso_modalidad_pagos')->where('id_curso', '=', $cursos->id)->delete();
                    foreach ($modalidades as $modalidad) {      // Se asocian las nuevas modalidades de pago al curso
                        $pago = ModalidadPago::where('nombre', '=', $modalidad)->get();
                        CursoModalidadPago::create([
                            'id_curso' => $cursos->id,
                            'id_modalidad_pago' => $pago[0]->id,
                        ]);
                    }
                    return redirect('/cursos');
                } else {    // Si el curso no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
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
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('eliminar_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea eliminar
                $curso = Curso::find($id);
                //Se desactiva el curso
                $curso->curso_activo = false;
                $curso->save(); // se guarda

                // Se redirige al usuario a la lista de cursos actualizada
                $data['errores'] = '';
                $data['cursos'] = Curso::orderBy('created_at')->get();
                foreach ($data['cursos'] as $curso) {
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);
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


    public function indexDesactivados()
    {
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_lista_cursos')) {   // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);
                }

                return view('cursos.desactivados', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function activar($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea activar
                $curso = Curso::find($id);
                //Se activa el curso
                $curso->curso_activo = true;
                $curso->save(); // se guarda

                // Se redirige al usuario a la lista de cursos actualizada
                $data['errores'] = '';
                $data['cursos'] = Curso::orderBy('created_at')->get();
                foreach ($data['cursos'] as $curso) {
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                }

                return view('cursos.desactivados', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

//    ------------------------ Participantes ------------------------------------
    public function cursoParticipantes($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('participantes_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['participantes'] = [];
                $data['curso'] = Curso::find($id);
                $curso_part = ParticipanteCurso::where('id_curso', '=', $id)->get();
                if($curso_part->count()){
                    foreach ($curso_part as $index => $curso) {
                        $data['participantes'][$index] = Participante::where('id', '=', $curso->id_participante)->get();
                    }
                }

                return view('cursos.participantes.participantes', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function cursoParticipantesAgregar($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_part_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = Curso::find($id);
                $arr = [];
                $todos = DB::table('participante_cursos')->select('id_participante')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_participante;
                }
                $no_estan = DB::table('participantes')->whereNotIn('id',$arr)->get();
                $participantes = ParticipanteCurso::where('id_curso', '!=', $id)->orderBy('id_participante')->get();
                $noParticipantes = ParticipanteCurso::where('id_curso', '=', $id)->orderBy('id_participante')->get();
                $participante = $participantes;
                $hay = false;
                $repetido = 0;
                $verificar = false;
                if ($participantes->count()) {
                    if($noParticipantes->count()) {
                        foreach ($participantes as $index => $part) {
                            foreach ($noParticipantes as $index1 => $parti) {
                                $partic = $parti->id_participante;
                                if ($partic == $part->id_participante) {
                                    unset($participante[$index]);
                                    $hay = true;
                                }else{
                                    if($part->id_participante == $repetido){
                                        unset($participante[$index]);
                                    }
                                }
                            }
                            $repetido = $part->id_participante;
                            if(($hay == false)){
                                $verificar = true;
                            }else{
                                $hay = false;
                            }
                        }
                        $participante = array($participante);
                        if ($participante != null) {
                            $participante = array_values($participante);
                        }
                        if($verificar) {
                            foreach ($participante[0] as $index => $datos) {
                                $data['participantes'][$index] = Participante::find($datos->id_participante);
                            }
                            if($no_estan != null) {
                                $tam = count($data['participantes']) - 1;
                                foreach ($no_estan as $datos) {
                                    $data['participantes'][$tam] = $datos;
                                    $tam++;
                                }
                            }
                        }else{
                            if($no_estan != null) {
                                foreach ($no_estan as $index => $datos) {
                                    $data['participantes'][$index] = $datos;
                                }
                            }else {
                                $data['participantes'] = '';
                            }
                        }
                    }else{
                        $data['participantes'] = Participante::all();
                    }
                }else{
                    $data['participantes'] = '';
                }

                return view('cursos.participantes.agregar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function cursoParticipantesGuardar($id_curso, $id_part) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_part_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $curso = Curso::find($id_curso);
                $participante = Participante::find($id_part);
                $existe = ParticipanteCurso::where('id_participante', '=', $id_part)->where('id_curso', '=', $id_curso)->get();

                if($existe->count()) {
                    Session::set('error', 'Ya existe el registro en la base de datos');
                    return $this->cursoParticipantesAgregar($id_curso);
                }else{
                    if ($curso != null || $participante != null) {
                        $part_curso = ParticipanteCurso::create(['id_participante' => $id_part,
                            'id_curso' => $id_curso,
                            'seccion' => 'B',
                        ]);
                        $part_curso->save();

                        if ($part_curso->save()) {
                            Session::set('mensaje', 'Participante agregado con éxito');
                            return $this->cursoParticipantesAgregar($id_curso);
                        } else {
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return $this->cursoParticipantesAgregar($id_curso);
                        }
                    } else {
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        return $this->index();
                    }
                }


            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function cursoParticipantesEliminar($id_curso, $id_part) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('eliminar_part_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
//                $curso = Curso::find($id_curso);
//                $participante = Participante::find($id_part);

                $part_curso = ParticipanteCurso::where('id_curso', '=', $id_curso)->where('id_participante', '=', $id_part)->first();

                DB::table('notas')->where('id_participante_curso', '=', $part_curso->id)->delete();
                DB::table('participante_cursos')->where('id', '=', $part_curso->id)->delete();

                $data['participantes'] = [];
                $data['curso'] = Curso::find($id_curso);
                $curso_part = ParticipanteCurso::where('id_curso', '=', $id_curso)->get();
                if($curso_part->count()){
                    foreach ($curso_part as $index => $curso) {
                        $data['participantes'][$index] = Participante::where('id', '=', $curso->id_participante)->get();
                    }
                }

                Session::set('mensaje', 'Usuario eliminado con éxito');
                return view('cursos.participantes.participantes', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
//    -------------------------------------------------------------------------------------------

//--------------------------------------- Profesores --------------------------------------------

    public function cursoProfesores($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('profesores_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['profesores'] = [];
                $data['curso'] = Curso::find($id);
                //dd('curso: '.$data['curso']);
                $curso_prof = ProfesorCurso::where('id_curso', '=', $id)->get();
//                dd($curso_prof);
                if($curso_prof->count()){
                    foreach ($curso_prof as $index => $curso) {
                        $data['profesores'][$index] = Profesor::where('id', '=', $curso->id_profesor)->get();
                    }
                }

                return view('cursos.profesores.profesores', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function cursoProfesoresAgregar($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_prof_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = Curso::find($id);
                $arr = [];
                $todos = DB::table('profesor_cursos')->select('id_profesor')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_profesor;
                }
                $no_estan = DB::table('profesores')->whereNotIn('id',$arr)->get();
                $profesores = ProfesorCurso::where('id_curso', '!=', $id)->orderBy('id_profesor')->get();
                $noProfesores = ProfesorCurso::where('id_curso', '=', $id)->orderBy('id_profesor')->get();
                $profesor = $profesores;
                $hay = false;
                $repetido = 0;
                $verificar = false;
                if ($profesores->count()) {
                    if($noProfesores->count()) {
                        foreach ($profesores as $index => $prof) {
                            foreach ($noProfesores as $index1 => $profe) {
                                $profe_id = $profe->id_profesor;
                                if ($profe_id == $prof->id_profesor) {
                                    unset($profesor[$index]);
                                    $hay = true;
                                }else{
                                    if($prof->id_profesor == $repetido){
                                        unset($profesor[$index]);
                                    }
                                }
                            }
                            $repetido = $prof->id_profesor;
                            if(($hay == false)){
                                $verificar = true;
                            }else{
                                $hay = false;
                            }
                        }
                        $profesor = array($profesor);
                        if ($profesor != null) {
                            $profesor = array_values($profesor);
                        }

                        if($verificar) {
                            foreach ($profesor[0] as $index => $datos) {
                                $data['profesores'][$index] = Profesor::find($datos->id_profesor);
                            }
                            if($no_estan != null) {
                                $tam = count($data['profesores']) - 1;
                                foreach ($no_estan as $datos) {
                                    $data['profesores'][$tam] = $datos;
                                    $tam++;
                                }
                            }
                        }else{
                            if($no_estan != null) {
                                foreach ($no_estan as $index => $datos) {
                                    $data['profesores'][$index] = $datos;
                                }
                            }else {
                                $data['profesores'] = '';
                            }
                        }
                    }else{
                        $data['profesores'] = Profesor::all();
                    }
                }else{
                    $data['profesores'] = '';
                }

                return view('cursos.profesores.agregar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
//-----------------------------------------------------------------------------------------------
}
