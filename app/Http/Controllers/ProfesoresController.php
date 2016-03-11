<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Curso;
use App\Models\Nota;
use App\Models\Participante;
use App\Models\ParticipanteCurso;
use App\Models\ProfesorCurso;
use App\Models\TipoCurso;
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
                $img_nueva = Session::get('cortar');

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
                    Storage::delete('/images/images_perfil/' . $request->file_viejo);
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
                    return view('participantes.editar-perfil', $data);
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

                $data['errores'] = '';
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

                return view('profesores.cursos', $data);

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
                $secciones = ProfesorCurso::where('id_curso', '=', $id_curso)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                $data['secciones'] = array_unique($arr);
//                dd($data['secciones']);

                return view('profesores.secciones', $data);

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
                $data['curso'] = Curso::find($id_curso);
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $participantes = ParticipanteCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->select('id_participante')->get();
//                dd($participantes);
                if($participantes != null) {
                    foreach ($participantes as $part) {
                        $data['participantes'] = Participante::where('id', '=', $part->id_participante)->get();
                    }
                }else{
                    $data['participantes'] = '';
                }
//                dd($data['participantes']);

                return view('profesores.participantes', $data);

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
                    }
                }else{
                    $data['notas'] = '';
                }

                return view('profesores.notas', $data);

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
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_notas')) {// Si el usuario posee los permisos necesarios continua con la acción
                dd($request->id);
                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $seccion = str_replace(' ', '', $seccion);
                $data['seccion'] = $seccion;
                $data['participante'] = Participante::find($id_part);
                $nota = Nota::findOrNew($request->id);
                $total = 0;

                $nota->evaluacion = $request->evaluacion;
                $nota->nota = $request->nota;

                $part = ParticipanteCurso::where('id_curso', '=', $id_curso)
                                            ->where('id_participante', '=', $id_part)
                                            ->where('seccion', '=', $seccion)
                                            ->select('id')->get();
                if($part->count()){
                    $notas = Nota::where('id_participante_curso', '=', $part[0]->id)->select('porcentaje')->get();
                    foreach ($notas as $not){
                        $total = $total + $not->porcentaje;
                    }
                    $total = $total + $request->porcentaje;
                    if($total > 100){
                        Session::set('error_mod', 'El porcentaje de la nota debe ser menor ya que el total supera el 100%');
                        return view('profesores.notas', $data);
                    }else{
                        $nota->porcentaje = $request->porcentaje;
                        $nota->save();

                    }
                }
                if ($nota->save()) {
                    if($request->id == null) {
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

}
