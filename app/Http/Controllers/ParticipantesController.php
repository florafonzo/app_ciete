<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\ParticipanteCurso;
use App\Models\TipoCurso;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Exception;
use DB;

use App\Models\Participante;
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

            if($usuario_actual->can('ver_perfil')) { // Si el usuario posee los permisos necesarios continua con la acción
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
            if($usuario_actual->can('ver_perfil')) {     // Si el usuario posee los permisos necesarios continua con la acción

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
    public function editarPerfil()
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->can('editar_perfil')) { // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
//                dd($data['datos']);

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
            if($usuario_actual->can('editar_perfil')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $usuario = User::find($id); // Se obtienen los datos del participante que se desea editar
                $participante = Participante::where('id_usuario', '=', $id)->get(); // Se obtiene el resto de los datos del participante que se desea editar

                $email = $request->email;
                // Se verifica si el correo ingresado es igual al anterior y si no lo es se verifica
                // que no conicida con los de las base de datos ya que debe ser único
                if (!($email == $usuario->email)) {

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

                // Se editan los datos del participante con los datos ingresados en el formulario
                $usuario->nombre = $request->nombre;
                $usuario->apellido = $request->apellido;
                $usuario->email = $email;



                $usuario->save();   // Se guardan los nuevos datos en la tabla Users


                // Se verifica si se colocó una imagen en el formulario
                if ($request->hasFile('imagen')) {
                    $imagen = $request->file('imagen');
                } else {
                    $imagen = $participante[0]->foto;
                }

                // Se editan los datos del participante deseado con los datos ingresados en el formulario
                $participante[0]->nombre = $request->nombre;
                $participante[0]->apellido = $request->apellido;
                $participante[0]->documento_identidad = $request->documento_identidad;
                $participante[0]->telefono = $request->telefono;
                $participante[0]->foto = $imagen;
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
                        $data['errores'] = '';
                        Session::set('mensaje','Datos guardados satisfactoriamente.');
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;
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
                        $data['fechas'][$index] = $curso->created_at;
                        $tipos = TipoCurso::where('id', '=', $cursos[0]->id_tipo)->get();
                        $data['tipo_curso'][$index] = $tipos[0]->nombre;
                    }
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
            if($usuario_actual->can('ver_notas_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
//                $data['cursos'] = [];
//                $data['fechas'] = [];
//                $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); //
//                $data['cursos_'] = ParticipanteCurso::where('id_participante', '=', $participante[0]->id)->get();
//
//                if ($data['cursos_']->count()) {
//                    foreach ($data['cursos_'] as $index => $curso) {
//                        $cursos  = Curso::where('id', '=', $curso->id_curso)->get();
//                        $data['cursos'][$index] = $cursos;
//                        $data['fechas'][$index] = $curso->created_at;
//                        $tipos = TipoCurso::where('id', '=', $cursos[0]->id_tipo)->get();
//                        $data['tipo_curso'][$index] = $tipos[0]->nombre;
//                    }
////                    dd($data['fechas']);
//                }



                return view('participantes.notas', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

}