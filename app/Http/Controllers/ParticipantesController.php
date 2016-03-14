<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Nota;
use App\Models\ParticipanteCurso;
use App\Models\ParticipanteWebinar;
use App\Models\TipoCurso;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use DB;
use DateTime;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\Participante;
use App\Models\Webinar;
use Illuminate\Http\Request;
use App\Http\Requests\ParticipanteRequest;
use App\user;


class ParticipantesController extends Controller {

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

            if($usuario_actual->can('ver_perfil_part')) { // Si el usuario posee los permisos necesarios continua con la acción
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
	 * Muestra los datos de perfil de participante
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

            if($usuario_actual->can('ver_perfil_part')) {     // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
//                dd($data['datos']);

                return view('participantes.ver-perfil', $data);

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

            if($usuario_actual->can('editar_perfil_part')) { // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;

                return view('participantes.editar-perfil', $data);

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
     * @param   int     $id                     //id del participante
     * @param   ParticipanteRequest $request    // Se validan los campos ingresados por el usuario antes guardarlos mediante el Request
	 *
	 * @return Response
	 */
	public function update(ParticipanteRequest $request,$id)
	{
        try {

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $usuario = User::find($id); // Se obtienen los datos del participante que se desea editar
                $participante = Participante::where('id_usuario', '=', $id)->get(); // Se obtiene el resto de los datos del participante que se desea editar
                $img_nueva = Input::get('cortar');//Session::get('cortar');

                $email = $request->email;
                // Se verifica si el correo ingresado es igual al anterior y si no lo es se verifica
                // que no conicida con los de las base de datos ya que debe ser único
                if ($email != $usuario->email) {

                    $existe = DB::table('users')->where('email', '=', $email)->first();

                    // Si el correo conicide con alguno de la base de datos se redirige al participante al
                    // formulario de edición indicandole el error
                    if ($existe) {
                        $data['errores'] = "El correo ya existe, ingrese uno diferente";
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;

                        return view('participantes.editar-perfil', $data);
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

                // Se editan los datos del participante deseado con los datos ingresados en el formulario
                $participante[0]->nombre = $request->nombre;
                $participante[0]->apellido = $request->apellido;
                $participante[0]->documento_identidad = $request->documento_identidad;
                $participante[0]->telefono = $request->telefono;
//                $participante[0]->foto = $imagen;
                $participante[0]->celular = $request->celular;
                $participante[0]->correo_alternativo = $request->correo_alternativo;
                $participante[0]->twitter = Input::get('twitter');
                $participante[0]->ocupacion = Input::get('ocupacion');
                $participante[0]->titulo_pregrado = Input::get('titulo');
                $participante[0]->universidad = Input::get('univ');

                $participante[0]->save(); // Se guardan los nuevos datos en la tabla Participentes


                //  Si se actualizaron con exito los datos del usuario, se actualizan los roles del usuario.
                if ($usuario->save()) {

                    if ($participante[0]->save()) {
                        Session::set('mensaje','Datos guardados satisfactoriamente.');
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;
                        $data['foto'] = $data['email'][0]->foto;
                        return view('participantes.ver-perfil', $data);

                    } else {    // Si el usuario no se ha actualizo con exito en la tabla Participante
                    // se redirige al formulario de creación y se le indica al usuario el error
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;

                        return view('participantes.editar-perfil', $data);
                    }
                    // Si el usuario no se ha actualizo con exito en la tabla Users
                    // se redirige al formulario de edicion y se le indica al usuario el error
                } else {
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                    $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;

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

            if($usuario_actual->can('ver_cursos_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['cursos'] = [];
                $data['fechas'] = [];
                $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); //
                $data['cursos_'] = ParticipanteCurso::where('id_participante', '=', $participante[0]->id)->get();
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

                return view('participantes.ver-cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function verNotasCurso($id)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $id_part = Participante::where('id_usuario', '=', $usuario_actual->id)->get();
                $curso_part = ParticipanteCurso::where('id_participante', '=', $id_part[0]->id)->where('id_curso', '=', $id)->get();
                $data['curso'] = Curso::where('id', '=', $id)->get();
                $data['notas'] = Nota::where('id_participante_curso', '=', $curso_part[0]->id)->get();
                if($data['notas']->count()) {
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

                return view('participantes.notas', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

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

            if($usuario_actual->can('ver_cursos_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['webinars'] = [];
                $data['fechas'] = [];
                $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); //
                $webinars_ = ParticipanteWebinar::where('id_participante', '=', $participante[0]->id)->get();
                if ($webinars_->count()) {
                    foreach ($webinars_ as $index => $web) {
                        $webinars  = Webinar::where('id', '=', $web->id_webinar)->get();
                        $data['webinars'][$index] = $webinars;
                        $data['seccion'][$index] = $web->seccion;
                        $data['inicio'][$index] = new DateTime($webinars[0]->fecha_inicio);
                        $data['fin'][$index] = new DateTime($webinars[0]->fecha_fin);
                    }

                }

                return view('participantes.ver-webinars', $data);

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

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                Session::flash('imagen', 'yes');
                return view('participantes.editar-perfil', $data);

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

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['ruta'] = Input::get('rutas');
                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                Session::flash('imagen', null);
                Session::flash('cortar', 'yes');
                return view('participantes.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }

    }

}
