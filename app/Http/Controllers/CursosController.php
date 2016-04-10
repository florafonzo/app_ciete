<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\CursoRequest;
use App\Http\Requests\CursoEditRequest;

use App\Models\CursoModalidadPago;
use App\Models\ModalidadCurso;
use App\Models\Participante;
use App\Models\ParticipanteCurso;
use App\Models\Curso;
use App\Models\ModalidadPago;
use App\Models\TipoCurso;
use App\Models\Profesor;
use App\Models\ProfesorCurso;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Maatwebsite\Excel\Facades\Excel as Excel;
use DateTime;
use Exception;



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
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['busq_'] = false;
                $data['busq'] = false;

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
     * Permite la busqueda segun los paraemetros dados por el usuario.
     *
     * @return Retorna la vista de la lista de cursos activos deseados.
     */
    public function buscar() {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_lista_cursos')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['cursos'] = array([]);
                $data['errores'] = '';
                $data['busq_'] = true;
                $param = Input::get('parametro');
                if($param == '0'){
                    $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos
                    foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                        $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                        $curso['tipo_curso'] = $tipo[0]->nombre;
                        $curso['inicio'] = new DateTime($curso->fecha_inicio);
                        $curso['fin'] = new DateTime($curso->fecha_fin);

                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('cursos.cursos', $data);
                }
                if ($param != 'tipo'){
                    if (empty(Input::get('busqueda'))) {
                        $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos
                        foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                            $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $curso['tipo_curso'] = $tipo[0]->nombre;
                            $curso['inicio'] = new DateTime($curso->fecha_inicio);
                            $curso['fin'] = new DateTime($curso->fecha_fin);

                        }
                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('cursos.cursos', $data);
                    }else{
                        $busq = Input::get('busqueda');
                    }
                }else{
                    $busq = Input::get('busqu');
                }
                if(($param != 'tipo')){
                    $data['cursos'] = Curso::where($param, 'ilike', '%'.$busq.'%')
                        ->where('curso_activo', '=', 'true')
                        ->orderBy('created_at')->get();
//                    dd($data['cursos']);
                    if($data['cursos']->count()) {
                        foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                            $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $curso['tipo_curso'] = $tipo[0]->nombre;
                            $curso['inicio'] = new DateTime($curso->fecha_inicio);
                            $curso['fin'] = new DateTime($curso->fecha_fin);
                        }
                    }
                }elseif($param == 'tipo'){
                    $data['cursos'] = Curso::where('id_tipo', '=', $busq)
                        ->where('curso_activo', '=', 'true')
                        ->orderBy('created_at')->get();
                    if($data['cursos']->count()) {
                        foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                            $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $curso['tipo_curso'] = $tipo[0]->nombre;
                            $curso['inicio'] = new DateTime($curso->fecha_inicio);
                            $curso['fin'] = new DateTime($curso->fecha_fin);
                        }
                    }
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
     * Permite la busqueda segun los paraemetros dados por el usuario.
     *
     * @return Retorna la vista de la lista de cursos desactivados deseados.
     */
    public function buscar2() {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_lista_cursos')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['cursos'] = array([]);
                $data['errores'] = '';
                $data['busq_'] = true;
                $param = Input::get('parametro');
                if($param == '0'){
                    $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos
                    foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                        $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                        $curso['tipo_curso'] = $tipo[0]->nombre;
                        $curso['inicio'] = new DateTime($curso->fecha_inicio);
                        $curso['fin'] = new DateTime($curso->fecha_fin);

                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('cursos.desactivados', $data);
                }
                if ($param != 'tipo'){
                    if (empty(Input::get('busqueda'))) {
                        $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos
                        foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                            $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $curso['tipo_curso'] = $tipo[0]->nombre;
                            $curso['inicio'] = new DateTime($curso->fecha_inicio);
                            $curso['fin'] = new DateTime($curso->fecha_fin);

                        }
                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('cursos.desactivados', $data);
                    }else{
                        $busq = Input::get('busqueda');
                    }
                }else{
                    $busq = Input::get('busqu');
                }
//                dd($busq);
                if(($param != 'tipo')){
                    $data['cursos'] = Curso::where($param, 'ilike', '%'.$busq.'%')
                        ->where('curso_activo', '=', 'false')
                        ->orderBy('created_at')->get();
//                    dd($data['cursos']);
                    if($data['cursos']->count()) {
                        foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                            $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $curso['tipo_curso'] = $tipo[0]->nombre;
                            $curso['inicio'] = new DateTime($curso->fecha_inicio);
                            $curso['fin'] = new DateTime($curso->fecha_fin);
                        }
                    }
                }elseif($param == 'tipo'){
                    $data['cursos'] = Curso::where('id_tipo', '=', $busq)
                        ->where('curso_activo', '=', 'false')
                        ->orderBy('created_at')->get();
                    if($data['cursos']->count()) {
                        foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                            $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $curso['tipo_curso'] = $tipo[0]->nombre;
                            $curso['inicio'] = new DateTime($curso->fecha_inicio);
                            $curso['fin'] = new DateTime($curso->fecha_fin);
                        }
                    }
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
                $data['activo_'] = false;
//                 Se eliminan los datos guardados en sesión anteriormente
                Session::forget('nombre');
                Session::forget('secciones');
                Session::forget('min');
                Session::forget('max');
                Session::forget('id_tipo');
                Session::forget('fecha_inicio');
                Session::forget('fecha_fin');
                Session::forget('especificaciones');
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
                $img_nueva = Input::get('cortar');
                $img_cargada = Input::get('img_');

                if($img_nueva == 'yes'){
                    $data['activo_'] =  true;
                    $data['ruta'] = Input::get('dir');
                    Session::flash('cortar', 'yes');
//                    Session::flash('img_carg', 'yes');
                }else{
                    $data['activo_'] =  false;
                }

                // Se guardan los datos ingresados por el usuario en sesion pra utilizarlos en caso de que se redirija
                // al usuari al formulario por algún error y no se pierdan los datos ingresados
                Session::set('nombre', $request->nombre);
                Session::set('secciones', $request->secciones);
                Session::set('min', $request->mini);
                Session::set('max', $request->maxi);
                Session::set('id_tipo', $request->id_tipo);
                Session::set('fecha_inicio', $request->fecha_inicio);
                Session::set('fecha_fin', $request->fecha_fin);
                Session::set('especificaciones', $request->especificaciones);
                Session::set('costo', $request->costo);
                Session::set('descripcion_carrusel', $request->descripcion_carrusel);

                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del curso
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    return view('cursos.crear', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        return view('cursos.crear', $data);
                    }
                }

                //se verifica que el MIN por seccion sea igual o menor al MAX
                if (($request->mini) > ($request->maxi)) {
                    Session::set('error', 'La cantidad minima de cupos por seccion debe ser igual o menor a la canidad maxima');
                    return view('cursos.crear', $data);
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
                    return view('cursos.crear', $data);

                }

                //Se verifica si el usuario seleccionó que el curso esté activo en el carrusel
                if ($activo_carrusel) {
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ((empty(Input::get('descripcion_carrusel'))) or ($img_cargada != 'yes')) {   // Si no están completos se
                                                                                                    // redirige al usuario indicandole el error
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        return view('cursos.crear', $data);
                    }
                }

                $modalidades = Input::get('modalidades_pago');  // Se obtienen las modalidades de pago seleccionadas


                // Se crea el nuevo curso con los datos ingresados
                $create2 = Curso::findOrNew($request->id);
                $create2->id_tipo = $request->id_tipo;
                $create2->id_modalidad_curso = $request->id_modalidad_curso;
                $create2->curso_activo = "true";
                $create2->secciones = $request->secciones;
                $create2->min = $request->mini;
                $create2->max = $request->maxi;
                $create2->nombre = $request->nombre;
                $create2->fecha_inicio = $request->fecha_inicio;
                $create2->fecha_fin = $request->fecha_fin;
                $create2->especificaciones = $request->especificaciones;
                $create2->costo = $request->costo;
                $create2->descripcion_carrusel = $request->descripcion_carrusel;
                $create2->activo_carrusel = $activo_carrusel;

                if($img_nueva == 'yes'){
                    $file = Input::get('dir');
                    $file = str_replace('data:image/png;base64,', '', $file);
                    $nombreTemporal = 'imagen_curso_' . date('dmY') . '_' . date('His') . ".jpg";
                    $create2->imagen_carrusel = $nombreTemporal;

                    $imagen = Image::make($file);
                    $payload = (string)$imagen->encode();
                    Storage::put(
                        '/images/images_carrusel/cursos/'. $nombreTemporal,
                        $payload
                    );
                }else{
                    $create2->imagen_carrusel = '';
                }

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
//                dd('edittt');
                $data['errores'] = '';
                $data['cursos'] = Curso::find($id); // Se obtiene la información del curso seleccionado
                $data['activo_'] =  $data['cursos']->activo_carrusel;
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
                    //if($pago->id_modalidad_pago == ($index + 1)){
                        $arr[$pago->id_modalidad_pago] = true;
                    //}
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
//                dd('update');
                $data['errores'] = '';
                $cursos = Curso::find($id);
                $img_nueva = Input::get('cortar');
                $img_cargada = Input::get('img_');

                if($img_nueva == 'yes'){
                    $data['activo_'] =  true;
                    $data['ruta'] = Input::get('dir');
                    Session::flash('cortar', 'yes');
                    Session::flash('img_carg', 'yes');
                }else{
                    $data['activo_'] =  $cursos->activo_carrusel;
                }

                $data['cursos'] = Curso::find($id);
                $data['inicio'] = new DateTime($cursos->fecha_inicio);
                $data['fin'] = new DateTime($cursos->fecha_fin);
                $data['tipo'] = $data['cursos']->id_tipo;
                $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                $data['modalidades_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                $data['modalidad_curso'] = $data['cursos']->id_modalidad_curso;
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $arr = [];
                foreach ($data['modalidad_pago'] as $index => $mod) {
                    $arr[$index] = false;
                }
                $pagos = CursoModalidadPago::where('id_curso', '=', $id)->orderBy('id_modalidad_pago')->get();
                foreach ($pagos as $index => $pago) {
                    $arr[$pago->id_modalidad_pago] = true;
                }
                $data['pagos'] = $arr;

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del curso
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    return view('cursos.editar', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        return view('cursos.editar', $data);
                    }
                }

                //se verifica que el MIN por seccion sea igual o menor al MAX
                if (($request->mini) > ($request->maxi)) {
                    Session::set('error', 'La cantidad minima de cupos por seccion debe ser igual o menor a la canidad maxima');
                    return view('cursos.editar', $data);
                }

                // Se verifica que el usuario haya seleccionado por lo menos una modalidad de pago
                if (empty(Input::get('modalidades_pago'))) {    // Si no ha seleccionado ningúna modalidad, se redirige al formulario
                    $data['errores'] = "Debe seleccionar una modalidad de pago.";
                    return view('cursos.editar', $data);

                }

                $activo_carrusel = false;
                //Se verifica si el usuario seleccionó que el curso esté activo en el carrusel
                if (($request->activo_carrusel) == "on") {
                    $activo_carrusel = true;
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ( (empty(Input::get('descripcion_carrusel'))) or ($img_cargada == null)) {// Si los campos no están completos se
                                                                                              // redirige al usuario indicandole el error
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        return view('cursos.editar', $data);
                    }
                }



                $modalidades = Input::get('modalidades_pago');  // Se obtienen las modalidades de pago seleccionadas

                // Se actualizan los datos del curso seleccionado
                $cursos->id_tipo = $request->id_tipo;
                $cursos->id_modalidad_curso = $request->id_modalidad_curso;
                $cursos->secciones = $request->secciones;
                $cursos->min = $request->mini;
                $cursos->max = $request->maxi;
                $cursos->nombre = $request->nombre;
                $cursos->fecha_inicio = $request->fecha_inicio;
                $cursos->fecha_fin = $request->fecha_fin;
                $cursos->especificaciones = $request->especificaciones;
                $cursos->costo = $request->costo;
                $cursos->activo_carrusel = $activo_carrusel;

                if(($img_nueva == 'yes') && ($activo_carrusel)){
                    $file = Input::get('dir');
                    if($cursos->imagen_carrusel != null){
                        Storage::delete('/images/images_carrusel/cursos/' . $request->file_viejo);
                    }
                    $file = str_replace('data:image/png;base64,', '', $file);
                    $nombreTemporal = 'imagen_curso_' . date('dmY') . '_' . date('His') . ".jpg";
                    $cursos->imagen_carrusel = $nombreTemporal;
                    $cursos->descripcion_carrusel = $request->descripcion_carrusel;

                    $imagen = Image::make($file);
                    $payload = (string)$imagen->encode();
                    Storage::put(
                        '/images/images_carrusel/cursos/'. $nombreTemporal,
                        $payload
                    );
                }else{
                    $cursos->descripcion_carrusel = '';
                    $cursos->imagen_carrusel = '';
                }

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
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['busq_'] = false;
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
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['errores'] = '';
                $data['busq_'] = false;
                $data['cursos'] = Curso::where('curso_activo', '=', 'false')
                                        ->orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos

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
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $curso = Curso::find($id);
                $data['busq_'] = false;
                //Se activa el curso
                $curso->curso_activo = true;
                $curso->save(); // se guarda

                // Se redirige al usuario a la lista de cursos actualizada
                $data['errores'] = '';
                $data['cursos'] = Curso::where('curso_activo', '=', 'false')
                                        ->orderBy('created_at')->get();
                foreach ($data['cursos'] as $curso) {
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

    public function cambiarImagen()
    {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
//                dd(Input::get('nombre'));
                //Se obtienen todos los tipos de cursos, modalidades de pago y modalidades de curso.
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                $data['activo_'] = true;
                Session::flash('imagen', 'yes');
                Session::flash('img_carg', 'yes');

                return view('cursos.crear', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }
    }

    public function procesarImagen() {

        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['ruta'] = Input::get('rutas');
                $data['errores'] = '';
                //Se obtienen todos los tipos de cursos, modalidades de pago y modalidades de curso.
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['modalidad_pago'] = ModalidadPago::all()->lists('nombre', 'id');
                $data['modalidad_curso'] = ModalidadCurso::all()->lists('nombre', 'id');
                $data['activo_'] = true;
                Session::flash('imagen', null);
                Session::flash('cortar', 'yes');
                Session::flash('img_carg', 'yes');

                return view('cursos.crear', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }

    }

    public function cambiarImagen1($id)
    {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['activo_'] = true;
                Session::flash('imagen', 'yes');
                Session::flash('img_carg', 'yes');

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
                    //if($pago->id_modalidad_pago == ($index + 1)){
                    $arr[$pago->id_modalidad_pago] = true;
                    //}
                }
                $data['pagos'] = $arr;
                return view('cursos.editar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }
    }

    public function procesarImagen1($id) {

        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['ruta'] = Input::get('rutas');
                $data['activo_'] = true;
                Session::flash('imagen', null);
                Session::flash('cortar', 'yes');
                Session::flash('img_carg', 'yes');
//                dd(Session::get('cortar'));

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
                    //if($pago->id_modalidad_pago == ($index + 1)){
                    $arr[$pago->id_modalidad_pago] = true;
                    //}
                }
                $data['pagos'] = $arr;

                return view('cursos.editar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }

    }

    //    Funcion para ordenar por apellido arreglos de objetos
    public function cmp($a, $b) {
//        dd('A: ' . $a . 'B: ' . $b);
        return strcmp($a->apellido, $b->apellido);
    }

//    ------------------------ Participantes ------------------------------------

    public function cursoSeccionesParts($id) {
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
                $data['curso'] = Curso::find($id);
                $arr = [];
                $secciones = ParticipanteCurso::where('id_curso', '=', $id)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('cursos.participantes.secciones', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function cursoParticipantes($id_curso, $seccion) {
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
                $data['busq'] = false;
                $data['participantes'] = [];
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $curso_part = ParticipanteCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->get();
                if($curso_part->count()){
                    foreach ($curso_part as $index => $curso) {
                        $data['participantes'][$index] = Participante::where('id', '=', $curso->id_participante)->orderBy('apellido')->get();
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

    /**
     * Permite la busqueda segun los paraemetros dados por el usuario.
     *
     * @return Retorna la vista de la lista de participantes deseados.
     */
    public function buscarParticipante($id_curso, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $data['participantes'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                $data['busq'] = true;
                if($param == '0'){
                    $data['busq'] = false;
                    $participantes = ParticipanteCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->select('id_participante')->get();
                    if($participantes != null) {
                        foreach ($participantes as $index => $part) {
                            $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('cursos.participantes.participantes', $data);
                }
                if (empty(Input::get('busqueda'))) {
                    $data['busq'] = false;
                    $participantes = ParticipanteCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->select('id_participante')->get();
                    if($participantes != null) {
                        foreach ($participantes as $index => $part) {
                            $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                        }
                    }
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return view('cursos.participantes.participantes', $data);
                }else{
                    $busq = Input::get('busqueda');
                }

                $participantes = Participante::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if($participantes != null) {
                    foreach ($participantes as $index => $part) {
                        $existe = ParticipanteCurso::where('id_curso', '=', $id_curso)
                            ->where('seccion', '=', $seccion)
                            ->where('id_participante', '=', $part->id)->get();
                        if($existe->count()) {
                            $data['participantes'][$index] = $part;
                        }
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


    public function cursoParticipantesAgregar($id_curso, $seccion) {
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
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $arr = [];
                $todos = DB::table('participante_cursos')->select('id_participante')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_participante;
                }
                $no_estan = DB::table('participantes')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ParticipanteCurso::all();
                if($existe->count()) {
                    $noParticipantes = ParticipanteCurso::where('id_curso', '=', $id_curso)->orderBy('id_participante')->select('id_participante')->get();

                    if ($noParticipantes->count()) {
                        foreach ($noParticipantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }

                        $participantes = ParticipanteCurso::where('id_curso', '!=', $id_curso)
                            ->whereNotIn('id_participante', $arr)
                            ->select('id_participante')
                            ->orderBy('id_participante')
                            ->get();
                        $arr = [];
                        foreach ($participantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }
                        $parts = array_unique($arr);

                        if($parts != null) {
                            foreach ($parts as $index => $id_part) {
                                $data['participantes'][$index] = Participante::find($id_part);
                            }
                        }else{
                            $data['participantes'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['participantes']);
                            foreach ($no_estan as $datos) {
                                $data['participantes'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['participantes'] != '') {
                            usort($data['participantes'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['participantes'] = Participante::orderBy('apellido')->get();
                    }
                }else{
                    $data['participantes'] = Participante::orderBy('apellido')->get();
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

    /**
     * Permite la busqueda segun los paraemetros dados por el usuario.
     *
     * @return Retorna la vista de la lista de participantes deseados.
     */
    public function buscarParticipanteAgregar($id_curso, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $data['participantes'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                if($param == '0'){
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return $this->cursoParticipantesAgregar($id_curso, $seccion);
                }
                if (empty(Input::get('busqueda'))) {
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return $this->cursoParticipantesAgregar($id_curso, $seccion);
                }else{
                    $busq = Input::get('busqueda');
                }

                $arr = [];
                $todos = DB::table('participante_cursos')->select('id_participante')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_participante;
                }
                $no_estan = DB::table('participantes')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ParticipanteCurso::all();
                if($existe->count()) {
                    $noParticipantes = ParticipanteCurso::where('id_curso', '=', $id_curso)->orderBy('id_participante')->select('id_participante')->get();

                    if ($noParticipantes->count()) {
                        foreach ($noParticipantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }

                        $participantes = ParticipanteCurso::where('id_curso', '!=', $id_curso)
                            ->whereNotIn('id_participante', $arr)
                            ->select('id_participante')
                            ->orderBy('id_participante')
                            ->get();
                        $arr = [];
                        foreach ($participantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }
                        $parts = array_unique($arr);

                        if($parts != null) {
                            foreach ($parts as $index => $id_part) {
                                $data['parts'][$index] = Participante::find($id_part);
                            }
                        }else{
                            $data['parts'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['parts']);
                            foreach ($no_estan as $datos) {
                                $data['parts'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['parts'] != '') {
                            usort($data['parts'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['parts'] = Participante::orderBy('apellido')->get();
                    }
                }else{
                    $data['parts'] = Participante::orderBy('apellido')->get();
                }
//                $participantes = Participante::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if($data['parts'] != null) {
                    foreach ($data['parts'] as $index => $part) {
                        $existe = Participante::where('id', '=', $part->id)
                            ->where($param, 'ilike', '%'.$busq.'%')->get();
                        if($existe->count()) {
                            $data['participantes'][$index] = $part;
                        }
                    }
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

    public function cursoParticipantesGuardar($id_curso, $seccion, $id_part) {
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
                $data['busq_'] = false;
                $data['busq'] = false;
                $curso = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $participante = Participante::find($id_part);
                $existe = ParticipanteCurso::where('id_participante', '=', $id_part)->where('id_curso', '=', $id_curso)->get();

                if($existe->count()) {
                    Session::set('error', 'Ya existe el registro en la base de datos');
                    return $this->cursoParticipantesAgregar($id_curso, $seccion);
                }else{
                    if ($curso != null || $participante != null) {
                        $max = $curso->max;
                        $cuantos = ParticipanteCurso::where('seccion', '=', $seccion)->where('id_curso', '=', $id_curso)->get();
                        $cuantos = count($cuantos);
                        if($cuantos >= $max){
                            Session::set('error', 'La seccion no tiene cupos disponibles');
                            return $this->cursoParticipantesAgregar($id_curso, $seccion);
                        }
                        else {
                            $part_curso = ParticipanteCurso::create([
                                'id_participante' => $id_part,
                                'id_curso' => $id_curso,
                                'seccion' => $seccion,
                            ]);
                            $part_curso->save();

                            if ($part_curso->save()) {
                                Session::set('mensaje', 'Participante agregado con éxito');
                                return $this->cursoParticipantesAgregar($id_curso, $seccion);
                            } else {
                                Session::set('error', 'Ha ocurrido un error inesperado');
                                return $this->cursoParticipantesAgregar($id_curso, $seccion);
                            }
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

    public function cursoParticipantesEliminar($id_curso, $seccion, $id_part) {
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
                $data['seccion'] = $seccion;
                $data['busq_'] = false;
                $data['busq'] = false;
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
                        $data['participantes'][$index] = Participante::where('id', '=', $curso->id_participante)->orderBy('apellido')->get();
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

    public function cursoSeccionesProfes($id) {
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
                $data['curso'] = Curso::find($id);
                $arr = [];
                $secciones = ParticipanteCurso::where('id_curso', '=', $id)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('cursos.profesores.secciones', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
    public function cursoProfesores($id_curso, $seccion) {
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
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['profesores'] = [];
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $curso_prof = ProfesorCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->get();
                if($curso_prof->count()){
                    foreach ($curso_prof as $index => $curso) {
                        $data['profesores'][$index] = Profesor::where('id', '=', $curso->id_profesor)->orderBy('apellido')->get();
                    }
                    $data['profesores'] = array($data['profesores']);
                    usort($data['profesores'], array($this, "cmp"));
                    $data['profesores'] = $data['profesores'][0];
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

    /**
     * Permite la busqueda segun los paraemetros dados por el usuario.
     *
     * @return Retorna la vista de la lista de profesores deseados.
     */
    public function buscarProfesor($id_curso, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $data['profesores'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                $data['busq'] = true;
                if($param == '0'){
                    $data['busq'] = false;
                    $profesores = ProfesorCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->select('id_profesor')->get();
                    if($profesores != null) {
                        foreach ($profesores as $index => $prof) {
                            $data['profesores'][$index] = Profesor::where('id', '=', $prof->id_profesor)->get();
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('cursos.profesores.profesores', $data);
                }
                if (empty(Input::get('busqueda'))) {
                    $data['busq'] = false;
                    $profesores = ProfesorCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->select('id_profesor')->get();
                    if($profesores != null) {
                        foreach ($profesores as $index => $prof) {
                            $data['profesores'][$index] = Profesor::where('id', '=', $prof->id_profesor)->get();
                        }
                    }
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return view('cursos.profesores.profesores', $data);
                }else{
                    $busq = Input::get('busqueda');
                }

                $profesores = Profesor::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if($profesores != null) {
                    foreach ($profesores as $index => $prof) {
                        $existe = ProfesorCurso::where('id_curso', '=', $id_curso)
                            ->where('seccion', '=', $seccion)
                            ->where('id_profesor', '=', $prof->id)->get();
                        if($existe->count()) {
                            $data['profesores'][$index] = $prof;
                        }
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

    public function cursoProfesoresAgregar($id_curso, $seccion) {
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
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $arr = [];
                $todos = DB::table('profesor_cursos')->select('id_profesor')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_profesor;
                }
                $no_estan = DB::table('profesores')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ProfesorCurso::all();
                if($existe->count()) {
                    $noProfesor = ProfesorCurso::where('id_curso', '=', $id_curso)->orderBy('id_profesor')->select('id_profesor')->get();

                    if ($noProfesor->count()) {
                        foreach ($noProfesor as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }

                        $profesores = ProfesorCurso::where('id_curso', '!=', $id_curso)
                            ->whereNotIn('id_profesor', $arr)
                            ->select('id_profesor')
                            ->orderBy('id_profesor')
                            ->get();
                        $arr = [];
                        foreach ($profesores as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }
                        $profes = array_unique($arr);

                        if($profes != null) {
                            foreach ($profes as $index => $id_prof) {
                                $data['profesores'][$index] = Profesor::find($id_prof);
                            }
                        }else{
                            $data['profesores'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['profesores']);
                            foreach ($no_estan as $datos) {
                                $data['profesores'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['profesores'] != '') {
                            usort($data['profesores'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['profesores'] = Profesor::orderBy('apellido')->get();
                    }
                }else{
                    $data['profesores'] = Profesor::orderBy('apellido')->get();
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

    /**
     * Permite la busqueda segun los paraemetros dados por el usuario.
     *
     * @return Retorna la vista de la lista de profesores deseados.
     */
    public function buscarProfesorAgregar($id_curso, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $data['profesores'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                $data['busq'] = true;
                if($param == '0'){
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return $this->cursoProfesoresAgregar($id_curso, $seccion);
                }
                if (empty(Input::get('busqueda'))) {
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return $this->cursoProfesoresAgregar($id_curso, $seccion);
                }else{
                    $busq = Input::get('busqueda');
                }

                $arr = [];
                $todos = DB::table('profesor_cursos')->select('id_profesor')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_profesor;
                }
                $no_estan = DB::table('profesores')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ProfesorCurso::all();
                if($existe->count()) {
                    $noProfesor = ProfesorCurso::where('id_curso', '=', $id_curso)->orderBy('id_profesor')->select('id_profesor')->get();

                    if ($noProfesor->count()) {
                        foreach ($noProfesor as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }

                        $profesores = ProfesorCurso::where('id_curso', '!=', $id_curso)
                            ->whereNotIn('id_profesor', $arr)
                            ->select('id_profesor')
                            ->orderBy('id_profesor')
                            ->get();
                        $arr = [];
                        foreach ($profesores as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }
                        $profes = array_unique($arr);

                        if($profes != null) {
                            foreach ($profes as $index => $id_prof) {
                                $data['profes'][$index] = Profesor::find($id_prof);
                            }
                        }else{
                            $data['profes'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['profes']);
                            foreach ($no_estan as $datos) {
                                $data['profes'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['profes'] != '') {
                            usort($data['profes'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['profes'] = Profesor::orderBy('apellido')->get();
                    }
                }else{
                    $data['profes'] = Profesor::orderBy('apellido')->get();
                }
//                $profesores = Profesor::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if( $data['profes'] != null) {
                    foreach ( $data['profes'] as $index => $prof) {
                        $existe = Profesor::where('id', '=', $prof->id)->where($param, 'ilike', '%'.$busq.'%')->get();
                        if($existe->count()) {
                            $data['profesores'][$index] = $prof;
                        }
                    }
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


    public function cursoProfesoresGuardar($id_curso, $seccion, $id_profesor) {
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
                $curso = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $data['busq_'] = false;
                $data['busq'] = false;
                $seccion = str_replace(' ', '', $seccion);
//                dd($seccion);
                $profesor = Profesor::find($id_profesor);
                $existe = ProfesorCurso::where('id_profesor', '=', $id_profesor)->where('id_curso', '=', $id_curso)->get();

                if($existe->count()) {
                    Session::set('error', 'Ya existe el registro en la base de datos');
                    return $this->cursoProfesoresAgregar($id_curso, $seccion);
                }else{
                    if ($curso != null || $profesor != null) {
                        $prof_curso = ProfesorCurso::create([
                            'id_profesor' => $id_profesor,
                            'id_curso' => $id_curso,
                            'seccion' => $seccion
                        ]);
                        dd($prof_curso);
                        $prof_curso->save();

                        if ($prof_curso->save()) {
                            Session::set('mensaje', 'Profesor agregado con éxito');
                            return $this->cursoProfesoresAgregar($id_curso, $seccion);
                        } else {
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return $this->cursoProfesoresAgregar($id_curso, $seccion);
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

    public function cursoProfesoresEliminar($id_curso, $seccion, $id_profesor) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('eliminar_prof_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['seccion'] = $seccion;
                $data['busq_'] = false;
                $data['busq'] = false;
                $prof_curso = ProfesorCurso::where('id_curso', '=', $id_curso)->where('id_profesor', '=', $id_profesor)->first();

                DB::table('profesor_cursos')->where('id', '=', $prof_curso->id)->delete();

                $data['profesores'] = [];
                $data['curso'] = Curso::find($id_curso);
                $curso_prof = ProfesorCurso::where('id_curso', '=', $id_curso)->get();
                if($curso_prof->count()){
                    foreach ($curso_prof as $index => $curso) {
                        $data['profesores'][$index] = Profesor::where('id', '=', $curso->id_profesor)->orderBy('apellido')->get();
                    }
                }

                Session::set('mensaje', 'Usuario eliminado con éxito');
                return view('cursos.profesores.profesores', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
//-----------------------------------------------------------------------------------------------
//-------------------------------Moodle----------------------------------------------------------

    public function seccionesMoodle($id) {
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
                $data['curso'] = Curso::find($id);
                $arr = [];
                $secciones = ParticipanteCurso::where('id_curso', '=', $id)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('cursos.moodle.secciones', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }


    public function listaMoodle($id, $seccion) {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('listar_alumnos')) {// Si el usuario posee los permisos necesarios continua con la acción
                $cursos = Curso::find($id);
                $seccion = str_replace(' ', '', $seccion);
                $curso_part = ParticipanteCurso::where('id_curso', '=', $id)->where('seccion', '=', $seccion)->get();
                $participante = [];
                if($curso_part->count()){
                    foreach ($curso_part as $index => $curso) {
                        $part = Participante::where('id', '=', $curso->id_participante)->get();
                        $usuario = User::where('id', '=', $part[0]->id_usuario)->get();
                        $username = explode("@", $usuario[0]->email);
                        $participante[$index][0] = $username[0];
                        $participante[$index][1] = $part[0]->documento_identidad;
                        $participante[$index][2] = $usuario[0]->nombre;
                        $participante[$index][3] = $usuario[0]->apellido;
                        $participante[$index][4] = $usuario[0]->email;
                        $participante[$index][5] = $cursos->nombre;
                    }
                }

//                dd($participantes);
//                $data = $participantes;

                Excel::create('Curso_'.$cursos->nombre.'_seccion_'.$seccion, function($excel) use($participante){
                    $excel->sheet('Sheetname', function($sheet) use($participante) {
                        $datos = ['username','password','firstname','lastname','email','course'];
                        $data = array(
                            array('data1', 'data2'),
                            array('data3', 'data4')
                        );
                        $data[0] = $datos;
                        foreach ($participante as $index => $part) {
                            $data[$index+1] = $part;
                        }

                        $sheet->fromArray($data, null, 'A1' , false, false);

                    });

                })->download('csv');

//                return ;

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch(Exception $e){
            return view('errors.error')->with('error',$e->getMessage());
        }
    }
//-----------------------------------------------------------------------------------------------



}
