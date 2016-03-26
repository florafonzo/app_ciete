<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Curso;
use App\Models\Nota;
use App\Models\Participante;
use App\Models\ParticipanteCurso;
use App\Models\ParticipanteWebinar;
use App\Models\ProfesorCurso;
use App\Models\ProfesorWebinar;
use App\Models\TipoCurso;
use App\Models\Webinar;
use Barryvdh\DomPDF\Facade as PDF;
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
use App\Http\Requests\ProfesorRequest;
use App\Http\Requests\CalificarRequest;

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
     * Muestra el formulario para editar los datos de perfil del usuario
     *
     * @return Response
     */
    public function editarPerfil($id)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
                Session::flash('imagen', 'yes');
            }

            if($usuario_actual->can('editar_perfil_profe')) { // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['datos'] = Profesor::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del profesor
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del profesor

                return view('profesores.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }


    /**
     * Actualiza los datos de perfil del participante.
     *
     * @param   int     $id                     //id del profesor
     * @param   ProfesorRequest $request    // Se validan los campos ingresados por el usuario antes guardarlos mediante el Request
     *
     * @return Response
     */
    public function update(ProfesorRequest $request, $id)
    {
        try {

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_perfil_profe')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $usuario = User::find($id); // Se obtienen los datos del profesor que se desea editar
                $profesor = Profesor::where('id_usuario', '=', $id)->get(); // Se obtiene el resto de los datos del profesor que se desea editar
                $img_nueva = Input::get('cortar');//Session::get('cortar');
                //dd('img_nuev:  ' .  $request->file_viejo);

                $email = $request->email;
                // Se verifica si el correo ingresado es igual al anterior y si no lo es se verifica
                // que no conicida con los de las base de datos, ya que debe ser único
                if ($email != $usuario->email) {

                    $existe = DB::table('users')->where('email', '=', $email)->first();

                    // Si el correo conicide con alguno de la base de datos se redirige al participante al
                    // formulario de edición indicandole el error
                    if ($existe) {
                        $data['errores'] = "El correo ya existe, ingrese uno diferente";
                        $data['datos'] = Profesor::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del profesor
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del profesor

                        return view('profesores.editar-perfil', $data);
                    }
                }

                if($img_nueva == 'yes'){

                    $file = Input::get('dir');
                    if($usuario->foto != null){
                       Storage::delete('/images/images_perfil/' . $request->file_viejo);
                    }                    
                    $file = str_replace('data:image/png;base64,', '', $file);
                    $nombreTemporal = 'perfil_' . date('dmY') . '_' . date('His') . ".jpg";
                    $usuario->foto = $nombreTemporal;

                    $imagen = Image::make($file);
                    $payload = (string)$imagen->encode();
                    Storage::put(
                        '/images/images_perfil/'. $nombreTemporal,
                        $payload
                    );
                }

                // Se editan los datos del participante con los datos ingresados en el formulario
                $usuario->nombre = $request->nombre;
                $usuario->apellido = $request->apellido;
                $usuario->email = $email;
                $usuario_actual->password = bcrypt($request->password);
                $usuario->save();   // Se guardan los nuevos datos en la tabla Users

                // Se editan los datos del profesor deseado con los datos ingresados en el formulario
                $profesor[0]->nombre = $request->nombre;
                $profesor[0]->apellido = $request->apellido;
                $profesor[0]->documento_identidad = $request->documento_identidad;
                $profesor[0]->telefono = $request->telefono;
                $profesor[0]->celular = $request->celular;

                $profesor[0]->save(); // Se guardan los nuevos datos en la tabla Participentes


                $data['datos'] = Profesor::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del profesor
                $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del profesor;

                //  Si se actualizaron con exito los datos del usuario, se actualizan los roles del usuario.
                if ($usuario->save()) {

                    if ($profesor[0]->save()) {
                        Session::set('mensaje','Datos guardados satisfactoriamente.');
                        $data['foto'] = $data['email'][0]->foto;
                        return view('profesores.ver-perfil', $data);

                    } else {    // Si el usuario no se ha actualizo con exito en la tabla Profesores
                        // se redirige al formulario de creación y se le indica al usuario el error
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        return view('profesores.editar-perfil', $data);
                    }
                    // Si el usuario no se ha actualizo con exito en la tabla Users
                    // se redirige al formulario de edicion y se le indica al usuario el error
                } else {
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return view('profesores.editar-perfil', $data);
                }
            }else{   // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

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

            if($usuario_actual->can('editar_perfil_profe')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['datos'] = Profesor::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del profesor
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del profesor;
                Session::flash('imagen', 'yes');
                return view('profesores.editar-perfil', $data);

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

            if($usuario_actual->can('editar_perfil_profe')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['ruta'] = Input::get('rutas');
                $data['errores'] = '';
                $data['datos'] = Profesor::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del profesor
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del profesor;
                Session::flash('imagen', null);
                Session::flash('cortar', 'yes');
                return view('profesores.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }

    }

//---------------------------------------- Cursos -------------------------------------------

    public function verCursos()
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_cursos_profe')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['errores'] = '';
                $data['busq'] = false;
                $data['busq_'] = false;
                $data['cursos'] = [];
                $data['fechas'] = [];
                $profesor = Profesor::where('id_usuario', '=', $usuario_actual->id)->get();
                $data['cursos_'] = ProfesorCurso::where('id_profesor', '=', $profesor[0]->id)->get();
                if ($data['cursos_']->count()) {
                    foreach ($data['cursos_'] as $index => $curso) {
                        $cursos  = Curso::where('id', '=', $curso->id_curso)->get();
                        $data['cursos'][$index] = $cursos;
//                        dd($cursos[0]->fecha_inicio);
                        $data['inicio'][$index] = new DateTime($cursos[0]->fecha_inicio);
                        $data['fin'][$index] = new DateTime($cursos[0]->fecha_fin);
                        $data['seccion'][$index] = $curso->seccion;
                        $tipos = TipoCurso::where('id', '=', $cursos[0]->id_tipo)->get();
                        $data['tipo_curso'][$index] = $tipos[0]->nombre;
                    }
//                    dd($);
                }

                return view('profesores.cursos.cursos', $data);

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
     * @return Retorna la vista de la lista de cursos deseados, dictados por el profesor.
     */
    public function buscarCurso() {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_cursos_profe')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['tipos'] = TipoCurso::all()->lists('nombre', 'id');
                $data['errores'] = '';
                $data['busq'] = true;
                $data['busq_'] = true;
                $data['cursos'] = [];
                $data['fechas'] = [];
                $profesor = Profesor::where('id_usuario', '=', $usuario_actual->id)->get();
                $cursos_ = ProfesorCurso::where('id_profesor', '=', $profesor[0]->id)->get();
                $param = Input::get('parametro');
                if($param == '0'){
                    $data['busq'] = false;
                    if ($cursos_->count()) {
                        foreach ($cursos_ as $index => $curso) {
                            $cursos  = Curso::where('id', '=', $curso->id_curso)->get();
                            $data['cursos'][$index] = $cursos;
                            $data['inicio'][$index] = new DateTime($cursos[0]->fecha_inicio);
                            $data['fin'][$index] = new DateTime($cursos[0]->fecha_fin);
                            $data['seccion'][$index] = $curso->seccion;
                            $tipos = TipoCurso::where('id', '=', $cursos[0]->id_tipo)->get();
                            $data['tipo_curso'][$index] = $tipos[0]->nombre;
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('profesores.cursos.cursos', $data);
                }
                if ($param != 'tipo'){
                    if (empty(Input::get('busqueda'))) {
                        $data['busq'] = false;
                        if ($data['cursos_']->count()) {
                            foreach ($data['cursos_'] as $index => $curso) {
                                $cursos  = Curso::where('id', '=', $curso->id_curso)->get();
                                $data['cursos'][$index] = $cursos;
                                $data['inicio'][$index] = new DateTime($cursos[0]->fecha_inicio);
                                $data['fin'][$index] = new DateTime($cursos[0]->fecha_fin);
                                $data['seccion'][$index] = $curso->seccion;
                                $tipos = TipoCurso::where('id', '=', $cursos[0]->id_tipo)->get();
                                $data['tipo_curso'][$index] = $tipos[0]->nombre;
                            }
                        }
                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('profesores.cursos.cursos', $data);
                    }else{
                        $busq = Input::get('busqueda');
                    }
                }else{
                    $busq = Input::get('busqu');
                }
                if(($param != 'tipo')){
                    $cursos = Curso::where($param, 'ilike', '%'.$busq.'%')
                        ->where('curso_activo', '=', 'true')
                        ->orderBy('created_at')->get();
                    if($cursos->count()) {
                        foreach ($cursos as $index => $curso) {
                            $dicta = ProfesorCurso::where('id_profesor','=', $profesor[0]->id)
                                                    ->where('id_curso', '=', $curso->id)->get();
                            if($dicta->count()){
                                $data['cursos'][$index] = $curso;
                            }
                        }
                        foreach ($data['cursos'] as $index => $curso) {
                            $data['inicio'][$index] = new DateTime($curso->fecha_inicio);
                            $data['fin'][$index] = new DateTime($curso->fecha_fin);
                            $data['seccion'][$index] = $curso->seccion;
                            $tipos = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $data['tipo_curso'][$index] = $tipos[0]->nombre;
                        }
//                        dd($data['cursos']);
                    }
                }elseif($param == 'tipo'){
                    $cursos = Curso::where('id_tipo', '=', $busq)
                        ->where('curso_activo', '=', 'true')
                        ->orderBy('created_at')->get();
                    if($cursos->count()) {
                        foreach ($cursos as $index => $curso) {
                            $dicta = ProfesorCurso::where('id_profesor','=', $profesor[0]->id)
                                ->where('id_curso', '=', $curso->id)->get();
                            if($dicta->count()){
                                $data['cursos'][$index] = $curso;
                            }
                        }
                        foreach ($data['cursos'] as $index => $curso) {
                            $data['inicio'][$index] = new DateTime($curso->fecha_inicio);
                            $data['fin'][$index] = new DateTime($curso->fecha_fin);
                            $data['seccion'][$index] = $curso->seccion;
                            $tipos = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                            $data['tipo_curso'][$index] = $tipos[0]->nombre;
                        }
                    }
                }
                return view('profesores.cursos.cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function verSeccionesCurso($id_curso)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_profe')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $arr = [];
//                $profesor = Profesor::where('id_usuario', '=', $usuario_actual->id)->get();
                $secciones = ParticipanteCurso::where('id_curso', '=', $id_curso)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('profesores.cursos.secciones', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function verParticipantesSeccion($id_curso, $seccion)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_profe')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq'] = false;
                $data['busq_'] = false;
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $participantes = ParticipanteCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->select('id_participante')->get();
//                dd($participantes);
                if($participantes != null) {
                    foreach ($participantes as $index => $part) {
                        $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                    }
                }else{
                    $data['participantes'] = '';
                }
//                dd($data['participantes']);

                return view('profesores.cursos.participantes', $data);

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
                    return view('profesores.cursos.participantes', $data);
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
                    return view('profesores.cursos.participantes', $data);
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

                return view('profesores.cursos.participantes', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function verNotasParticipante($id_curso, $seccion, $id_part)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_profe')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['calificacion'] = '';
                $data['curso'] = Curso::find($id_curso);
                $seccion = str_replace(' ', '', $seccion);
                $data['seccion'] = $seccion;
                $data['participante'] = Participante::find($id_part);
                $arr = [];
                $participante = ParticipanteCurso::where('id_participante', '=', $id_part)
                                ->where('id_curso', '=', $id_curso)
                                ->where('seccion', '=', $seccion)
                                ->select('id')->get();

                if($participante->count()) {
                    $data['notas'] = Nota::where('id_participante_curso', '=', $participante[0]->id)->get();
                    if($data['notas']->count()){
                        $data['promedio'] = 0;
                        $porcentaje = 0;
                        foreach ($data['notas'] as $nota) {
                            $calif = $nota->nota;
                            $porcent = $nota->porcentaje;
                            $porcentaje =  ($porcentaje + $porcent);
                            $data['promedio'] = $data['promedio'] + ($calif * ($porcent / 100));
                        }
                        $data['porcentaje'] =  100 - $porcentaje;
                    }/*else{
                        $data['notas'] = [];
                    }*/
                }else{
                    $data['notas'] = '';
                }
                //dd($data['notas']);

                return view('profesores.cursos.notas', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function store(CalificarRequest $request, $id_curso, $seccion, $id_part) {

        try{
//            dd('ahhhhhh2');
            //Verificación de    los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_notas')) {// Si el usuario posee los permisos necesarios continua con la acción
                $id = Input::get('id_nota');
                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $seccion = str_replace(' ', '', $seccion);
                $data['seccion'] = $seccion;
                $data['participante'] = Participante::find($id_part);
                $nota = Nota::findOrNew($id);

                $total = 0;

                $part = ParticipanteCurso::where('id_curso', '=', $id_curso)
                                            ->where('id_participante', '=', $id_part)
                                            ->where('seccion', '=', $seccion)
                                            ->select('id')->get();
                if($part->count()){
                    $notas = Nota::where('id_participante_curso', '=', $part[0]->id)->select('porcentaje')->get();
                    if($notas->count()){
                        foreach ($notas as $not){
                            $total = $total + $not->porcentaje;
                        }
                        $total = $total + $request->porcentaje;
                        if($total > 100){
                            Session::set('error_mod', 'El porcentaje de la nota debe ser menor ya que el total supera el 100%');
                            return view('profesores.cursos.notas', $data);
                        }
                    }    
                    $nota->id_participante_curso = $part[0]->id;
                    $nota->evaluacion = $request->evaluacion;
                    $nota->nota = $request->nota;
                    $nota->porcentaje = $request->porcentaje;
                    $nota->save();
                }
                if ($nota->save()) {
                    if($id == null) {
//                        dd('holaaaaa');
                        Session::set('mensaje', 'Nota creada satisfactoriamente.');
                        return $this->verNotasParticipante($id_curso, $seccion, $id_part);
                    }else{
                        Session::set('mensaje', 'Nota editada satisfactoriamente.');
                        return $this->verNotasParticipante($id_curso, $seccion, $id_part);
                    }

                } else{
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return $this->verNotasParticipante($id_curso, $seccion,$id_part);
                }


            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }


    public function eliminarNotasParticipante($id_curso, $seccion, $id_part, $id_nota) {

        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_notas')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';

                DB::table('notas')->where('id', '=', $id_nota)->delete();

                return $this->verNotasParticipante($id_curso, $seccion, $id_part);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }
//-------------------------------------------------------------------------------------------

//---------------------------------------- Webinars -----------------------------------------

    public function verWebinars()
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_cursos_profe')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['webinars'] = [];
                $data['fechas'] = [];
                $profesor = Profesor::where('id_usuario', '=', $usuario_actual->id)->get();
                $data['webinars_'] = ProfesorWebinar::where('id_profesor', '=', $profesor[0]->id)->get();
                if ($data['webinars_']->count()) {
                    foreach ($data['webinars_'] as $index => $web) {
                        $webinars  = Webinar::where('id', '=', $web->id_webinar)->get();
                        $data['webinars'][$index] = $webinars;
                        $data['inicio'][$index] = new DateTime($webinars[0]->fecha_inicio);
                        $data['fin'][$index] = new DateTime($webinars[0]->fecha_fin);
                        $data['seccion'][$index] = $web->seccion;
                    }
//                    dd($);
                }

                return view('profesores.webinars.webinars', $data);

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
     * @return Retorna la vista de la lista de cursos deseados, dictados por el profesor.
     */
    public function buscarWebinar() {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_cursos_profe')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq'] = true;
                $data['busq_'] = true;
                $data['webinars'] = '';
                $data['fechas'] = [];
                $profesor = Profesor::where('id_usuario', '=', $usuario_actual->id)->get();
                $param = Input::get('parametro');
                if($param == '0'){
                    $data['busq'] = false;
                    $data['webinars_'] = ProfesorWebinar::where('id_profesor', '=', $profesor[0]->id)->get();
                    if ($data['webinars_']->count()) {
                        foreach ($data['webinars_'] as $index => $web) {
                            $webinars  = Webinar::where('id', '=', $web->id_webinar)->get();
                            $data['webinars'][$index] = $webinars;
                            $data['inicio'][$index] = new DateTime($webinars[0]->fecha_inicio);
                            $data['fin'][$index] = new DateTime($webinars[0]->fecha_fin);
                            $data['seccion'][$index] = $web->seccion;
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('profesores.webinars.webinars', $data);
                }

                if (empty(Input::get('busqueda'))) {
                    $data['busq'] = false;
                    $data['webinars_'] = ProfesorWebinar::where('id_profesor', '=', $profesor[0]->id)->get();
                    if ($data['webinars_']->count()) {
                        foreach ($data['webinars_'] as $index => $web) {
                            $webinars  = Webinar::where('id', '=', $web->id_webinar)->get();
                            $data['webinars'][$index] = $webinars;
                            $data['inicio'][$index] = new DateTime($webinars[0]->fecha_inicio);
                            $data['fin'][$index] = new DateTime($webinars[0]->fecha_fin);
                            $data['seccion'][$index] = $web->seccion;
                        }
                    }
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return view('profesores.webinars.webinars', $data);

                }else{
                    $busq = Input::get('busqueda');
                }

                $webinars = Webinar::where($param, 'ilike', '%'.$busq.'%')
                                    ->where('webinar_activo', '=', 'true')
                                    ->orderBy('created_at')->get();
                if($webinars->count()) {
                    foreach ($webinars as $index => $web) {
                        $dicta = ProfesorWebinar::where('id_profesor','=', $profesor[0]->id)
                                                ->where('id_webinar', '=', $web->id)->get();
                        if($dicta->count()){
                            $data['webinars'][$index] = $web;
                        }
                    }
                    foreach ($data['webinars'] as $index => $web) {
                        $data['inicio'][$index] = new DateTime($web->fecha_inicio);
                        $data['fin'][$index] = new DateTime($web->fecha_fin);
                        $data['seccion'][$index] = $web->seccion;
                    }
//                        dd($data['cursos']);
                }
                return view('profesores.webinars.webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }


    public function verSeccionesWebinar($id)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_profe')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id);
                $arr = [];
//                $profesor = Profesor::where('id_usuario', '=', $usuario_actual->id)->get();
                $secciones = ParticipanteWebinar::where('id_webinar', '=', $id)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('profesores.webinars.secciones', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function verParticipantesWebinar($id, $seccion)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_profe')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq'] = false;
                $data['busq_'] = false;
                $data['webinar'] = Webinar::find($id);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $participantes = ParticipanteWebinar::where('id_webinar', '=', $id)->where('seccion', '=', $seccion)->select('id_participante')->get();
//                dd($participantes);
                if($participantes != null) {
                    foreach ($participantes as $index => $part) {
                        $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                    }
                }else{
                    $data['participantes'] = '';
                }

                return view('profesores.webinars.participantes', $data);

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
    public function buscarParticipanteW($id, $seccion) {
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
                $data['webinar'] = Webinar::find($id);
                $data['seccion'] = $seccion;
                $data['participantes'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                $data['busq'] = true;
                if($param == '0'){
                    $data['busq'] = false;
                    $participantes = ParticipanteWebinar::where('id_webinar', '=', $id)->where('seccion', '=', $seccion)->select('id_participante')->get();
                    if($participantes != null) {
                        foreach ($participantes as $index => $part) {
                            $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('profesores.webinars.participantes', $data);
                }
                if (empty(Input::get('busqueda'))) {
                    $data['busq'] = false;
                    $participantes = ParticipanteWebinar::where('id_webinar', '=', $id)->where('seccion', '=', $seccion)->select('id_participante')->get();
                    if($participantes != null) {
                        foreach ($participantes as $index => $part) {
                            $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                        }
                    }
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return view('profesores.webinars.participantes', $data);
                }else{
                    $busq = Input::get('busqueda');
                }

                $participantes = Participante::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if($participantes != null) {
                    foreach ($participantes as $index => $part) {
                        $existe = ParticipanteWebinar::where('id_webinar', '=', $id)
                            ->where('seccion', '=', $seccion)
                            ->where('id_participante', '=', $part->id)->get();
                        if($existe->count()) {
                            $data['participantes'][$index] = $part;
                        }
                    }
                }

                return view('profesores.webinars.participantes', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

//-------------------------------------------------------------------------------------------

    public function generarLista($id, $seccion) {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('listar_alumnos')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = Curso::find($id);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $participantes = ParticipanteCurso::where('id_curso', '=', $id)->where('seccion', '=', $seccion)->select('id_participante')->get();
                if($participantes != null) {
                    foreach ($participantes as $index => $part) {
                        $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                    }
                }else{
                    $data['participantes'] = '';
                }

                if($data['participantes'] != '') {
                    usort($data['participantes'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                }

                $pdf = PDF::loadView('profesores.cursos.lista',$data);
                return $pdf->stream("Listado curso - ".$data['curso']->nombre." - seccion ".$seccion.".pdf", array('Attachment'=>0));

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch(Exception $e){
            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function generarListaW($id, $seccion) {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('listar_alumnos')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $participantes = ParticipanteWebinar::where('id_webinar', '=', $id)->where('seccion', '=', $seccion)->select('id_participante')->get();
                if($participantes != null) {
                    foreach ($participantes as $index => $part) {
                        $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                    }
                }else{
                    $data['participantes'] = '';
                }

                if($data['participantes'] != '') {
                    usort($data['participantes'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                }

                $pdf = PDF::loadView('profesores.webinars.lista',$data);
                return $pdf->stream("Listado webinar - ".$data['webinar']->nombre." - seccion ".$seccion.".pdf", array('Attachment'=>0));

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch(Exception $e){
            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    //    Funcion para ordenar por apellido arreglos de objetos
    public function cmp($a, $b) {
        return strcmp($a[0]->apellido, $b[0]->apellido);
    }
}
