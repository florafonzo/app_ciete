<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use App\Models\Participante;
use App\Models\Profesor;
//use Illuminate\Validation\Validator;
use App\Http\Requests\UsuarioRequest;
use App\Http\Requests\UsuarioEditarRequest;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\RedirectResponse;
use DB;
use Exception;

use Illuminate\Http\Request;

class UsuariosController extends Controller {

    /**
     * Muestra la vista de la lista de usuarios si posee los permisos necesarios.
     *
     * @return Retorna la vista de la lista de usuarios.
     */
	public function index() {
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
                if(($permiso->name) == 'ver_usuarios'){
                    $si_puede = true;
                }
            }

            if($si_puede){  // Si el usuario posee los permisos necesarios continua con la acción

                $data['usuarios'] = User::all();

                foreach ($data['usuarios'] as $usuario) { //se asocian los roles a cada usuario
                    $usuario['roles'] = $usuario->roles()->get();
                    //                dd($usuario['rol']);
                }
                return view('usuarios.usuarios', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
		}
		catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
			
	}

    /**
     * Muestra el formulario para crear un nuevo usuario si posee los permisos necesarios.
     *
     * @return Retorna la vista del formulario vacío.
     */
	public function create() {
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
                if(($permiso->name) == 'ver_usuarios'){
                    $si_puede = true;
                }
            }

            if($si_puede){  // Si el usuario posee los permisos necesarios continua con la acción

                //se eliminan los datos guardados en sesion anteriormente
                Session::forget('nombre');
                Session::forget('apellido');
                Session::forget('email');
                Session::forget('documento_identidad');
                Session::forget('telefono');
                Session::forget('celular');

                $data['roles'] = Role::all()->lists('display_name', 'id');
                $data['errores'] = '';

                return view('usuarios.crear', $data);

            }else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
	    }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

    /**
     * Guarda el nuevo usuario si el usuario posee los permisos necesarios.
     *
     * @param   UsuarioRequest    $request (Se validan los campos ingresados por el usuario antes guardarlos mediante el Request)
     *
     * @return Retorna la vista de la lista de usuarios con el nuevo usuario agregado.
     */
	public function store(UsuarioRequest $request)	{

        try {

            //Verificación de los permisos del usuario para poder realizar esta acción
            $permisos = [];
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'ver_usuarios'){
                    $si_puede = true;
                }
            }

            if($si_puede){  // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $create = User::create([
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);

                $usuario = User::find($create->id);
                $roles = $request->id_rol;
                $create2 = 0;

                if ($request->hasFile('imagen')) {
                    $imagen = $request->file('imagen');
                } else {
                    $imagen = '';
                }

                if (($request->es_participante) == 'si') {

                    $create2 = Participante::findOrNew($request->id);
                    $create2->id_usuario = $usuario->id;
                    $create2->nombre = $request->nombre;
                    $create2->apellido = $request->apellido;
                    $create2->documento_identidad = $request->documento_identidad;
                    $create2->foto = $imagen;
                    $create2->telefono = $request->telefono;
                    $create2->celular = $request->celular;
                    $create2->correo_alternativo = $request->email_alternativo;
                    $create2->twitter = Input::get('twitter');
                    $create2->ocupacion = Input::get('ocupacion');
                    $create2->titulo_pregrado = Input::get('titulo');
                    $create2->universidad = Input::get('univ');

                } elseif (($request->es_participante) == 'no') {

                    if (empty(Input::get('id_rol'))) {

                        DB::table('users')->where('id', '=', $usuario->id)->delete();
                        $data['errores'] = "Debe seleccionar un rol";
                        $data['roles'] = Role::all()->lists('display_name', 'id');

                        Session::set('nombre', $request->nombre);
                        Session::set('apellido', $request->apellido);
                        Session::set('email', $request->email);
                        Session::set('documento_identidad', $request->documento_identidad);
                        Session::set('telefono', $request->telefono);
                        Session::set('celular', $request->celular);

                        return view('usuarios.crear', $data);

                    } else {

                        $create2 = Profesor::create([
                            'id_usuario' => $usuario->id,
                            'nombre' => $request->nombre,
                            'apellido' => $request->apellido,
                            'documento_identidad' => $request->documento_identidad,
                            'foto' => $imagen,
                            'telefono' => $request->telefono,
                            'celular' => $request->celular
                        ]);
                    }
                }

                if ($create->save()) {
                    if ($create2->save()) {
                        if (($request->es_participante) == 'si') {
                            $role = DB::table('roles')->where('name', '=', 'participante')->first();
                            $usuario->attachRole($role->id);
                        } elseif (($request->es_participante) == 'no') {
                            foreach ($roles as $rol) {
                                $role = DB::table('roles')->where('display_name', '=', $rol)->first();
                                $usuario->attachRole($role->id);
                            }
                        }
                        return redirect('/usuarios');
                    } else {
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        DB::table('users')->where('id', '=', $usuario->id)->delete();
                        return view('usuarios.crear');
                    }
                } else {
                    Session::set('error', 'Ha ocur rido un error inesperado');
                    return view('usuarios.crear');
                }
            }else{   // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }

        }
        catch (Exception $e)
        {
            return view('errors.error')->with('error',$e->getMessage());
        }

	}

    /**
     * Se muestra el formulario de edicion de usuario si posee los permisos necesarios.
     *
     * @param  int  $id (id del usuario seleccionado)
     *
     * @return Retorna vista del formulario para el editar el usuario deseado.
     */
	public function edit($id) {
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
                if(($permiso->name) == 'ver_usuarios'){
                    $si_puede = true;
                }
            }

            if($si_puede){  // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['es_participante'] = false;
                $usuario = User::find($id);
                $data['usuarios'] = $usuario;
                $userRoles = $data['usuarios']->roles()->get();
                $data['rol'] = $userRoles;
                $data['roles'] = Role::all()->lists('display_name', 'id');

                foreach ($userRoles as $role) {
                    if (($role->name) == 'participante') {
                        $data['es_participante'] = true;
                        $data['datos_usuario'] = DB::table('participantes')->where('id_usuario', '=', $usuario->id)->first();
                    } else {
                        $data['datos_usuario'] = DB::table('profesores')->where('id_usuario', '=', $usuario->id)->first();
                        //                    dd($data['datos_usuario']);
                    }
                    //                dd($data['datos_usuario']);
                    break;
                }

                return view('usuarios.edit', $data);
            }else{   // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
	    }
	    catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }


	}

    /**
     * Actualiza los datos del usuario seleccionado si posee los permisos necesarios
     *
     * @param  int  $id (id del usuario seleccionado)
     * @param  UsuarioRequest  $request (Se validan los campos ingresados por el usuario antes guardarlos mediante el Request)
     *
     * @return Retorna la lista de usuarios con los datos actualizados.
     */
	public function update(UsuarioEditarRequest $request, $id) {

		try {

            //Verificación de los permisos del usuario para poder realizar esta acción
            $permisos = [];
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'ver_usuarios'){
                    $si_puede = true;
                }
            }

            if($si_puede) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $usuario = User::find($id);
                $userRoles = $usuario->roles()->get();
                $es_participante = false;

                foreach ($userRoles as $role) {
                    if (($role->name) == 'participante') {
                        $es_participante = true;
                    }
                    break;
                }


                $email = $request->email;

                if (!($email == $usuario->email)) {

                    $existe = DB::table('users')->where('email', '=', $email)->first();

                    if ($existe) {
                        $data['errores'] = "El correo ya existe, ingrese uno diferente";
                        $data['es_participante'] = false;
                        $usuario = User::find($id);
                        $data['usuarios'] = $usuario;
                        $userRoles = $data['usuarios']->roles()->get();
                        $data['rol'] = $userRoles;
                        $data['roles'] = Role::all()->lists('display_name', 'id');

                        foreach ($userRoles as $role) {
                            if (($role->name) == 'participante') {
                                $data['es_participante'] = true;
                                $data['datos_usuario'] = DB::table('participantes')->where('id_usuario', '=', $usuario->id)->first();
                            } else {
                                $data['datos_usuario'] = DB::table('profesores')->where('id_usuario', '=', $usuario->id)->first();
                            }
                            break;
                        }

                        return view('usuarios.edit', $data);
                    }
                }

                $password = bcrypt($request->password);

                $usuario->nombre = $request->nombre;
                $usuario->apellido = $request->apellido;
                $usuario->email = $email;
                $usuario->password = $password;

                $roles = Input::get('id_rol');

                if ($es_participante) {
                    $usuario->save();

                    $tipo_usuario = Participante::find(1)->where('id_usuario', '=', $id)->first();

                    if ($request->hasFile('imagen')) {
                        $imagen = $request->file('imagen');
                    } else {
                        $imagen = $tipo_usuario->foto;
                    }

                    $tipo_usuario->nombre = $request->nombre;
                    $tipo_usuario->apellido = $request->apellido;
                    $tipo_usuario->documento_identidad = $request->documento_identidad;
                    $tipo_usuario->telefono = $request->telefono;
                    $tipo_usuario->foto = $imagen;
                    $tipo_usuario->celular = $request->celular;
                    $tipo_usuario->correo_alternativo = $request->correo_alternativo;
                    $tipo_usuario->twitter = Input::get('twitter');
                    $tipo_usuario->ocupacion = Input::get('ocupacion');
                    $tipo_usuario->titulo_pregrado = Input::get('titulo');
                    $tipo_usuario->universidad = Input::get('univ');

                    $tipo_usuario->save();

                } else {

                    if (empty(Input::get('id_rol'))) {

                        $data['errores'] = "Debe seleccionar un Rol";
                        $data['es_participante'] = false;
                        $usuario = User::find($id);
                        $data['usuarios'] = $usuario;
                        $userRoles = $data['usuarios']->roles()->get();
                        $data['rol'] = $userRoles;
                        $data['roles'] = Role::all()->lists('display_name', 'id');

                        foreach ($userRoles as $role) {
                            if (($role->name) == 'participante') {
                                $data['es_participante'] = true;
                                $data['datos_usuario'] = DB::table('participantes')->where('id_usuario', '=', $usuario->id)->first();
                            } else {
                                $data['datos_usuario'] = DB::table('profesores')->where('id_usuario', '=', $usuario->id)->first();
                            }
                            break;
                        }

                        return view('usuarios.edit', $data);

                    } else {

                        $usuario->save();
                        $tipo_usuario = Profesor::find(1)->where('id_usuario', '=', $id)->first();

                        if ($request->hasFile('imagen')) {
                            $imagen = $request->file('imagen');
                        } else {
                            $imagen = $tipo_usuario->foto;
                        }

                        $tipo_usuario->nombre = $request->nombre;
                        $tipo_usuario->apellido = $request->apellido;
                        $tipo_usuario->documento_identidad = $request->documento_identidad;
                        $tipo_usuario->telefono = $request->telefono;
                        $tipo_usuario->foto = $imagen;
                        $tipo_usuario->celular = $request->celular;

                        $tipo_usuario->save();
                    }
                }

                if ($usuario->save()) {
                    if ($tipo_usuario->save()) {
                        if (!$es_participante) {
                            DB::table('role_user')->where('user_id', '=', $id)->delete();
                            foreach ($roles as $rol) {
                                $role = DB::table('roles')->where('display_name', '=', $rol)->first();
                                $usuario->attachRole($role->id);
                            }
                        }
                        return redirect('/usuarios');
                    } else {
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        DB::table('users')->where('id', '=', $usuario->id)->delete();
                        return view('usuarios.edit');
                    }
                } else {
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return view('usuarios.edit');
                }
            }else{   // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
        }
        catch (Exception $e) {
            return view('errors.error')->with('error',$e->getMessage());
        }        
    
   
	}

    /**
     * Elimina el usuario requerido.
     *
     * @param  int  $id
     *
     * @return Retorna la vista de la lista de cursos actualizada.
     */
	public function destroy($id) {
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
                if(($permiso->name) == 'ver_usuarios'){
                    $si_puede = true;
                }
            }

            if($si_puede) {  // Si el usuario posee los permisos necesarios continua con la acción

                $usuario = User::find($id);
                $roles = $usuario->roles()->get();

                //           dd($roles[0]->name );
                if (($roles[0]->name) == 'admin') {
                    $data['errores'] = "El usuario Administrador no puede ser eliminado";
                    return view('usuarios.usuarios', $data);
                }

                User::destroy($id);
                /*$affectedRows = User::where('id', '=', $id)->delete();*/
                $data['usuarios'] = User::all();

                foreach ($data['usuarios'] as $usuario) {
                    $usuario['rol'] = $usuario->roles()->first();

                }

                return view('usuarios.usuarios', $data);

            }else{   // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
	    }
	    catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        } 
	        
	}

}
