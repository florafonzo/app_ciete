<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use App\Models\Participante;
use App\Models\Profesor;
use App\Models\Pais;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Ciudad;
use App\Models\Parroquia;
use Illuminate\Validation\Validator;
use App\Http\Requests\UsuarioRequest;
use App\Http\Requests\UsuarioEditarRequest;

use Illuminate\Support\Facades\Auth;
//use Validator;

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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['usuarios'] = User::orderBy('id')->get();
                $data['errores'] = '';
                $data['roles'] = Role::all()->lists('display_name', 'id');
                $data['busq'] = false;
                $data['busq_'] = false;

                foreach ($data['usuarios'] as $usuario) { //se asocian los roles a cada usuario
                    $usuario['roles'] = $usuario->roles()->get();
                    $ci = Participante::where('id_usuario', '=', $usuario->id)->get();
                    if($ci->count()){
                        $usuario['doc_id'] = $ci[0]->documento_identidad;
                    }else{
                        $ci = Profesor::where('id_usuario', '=', $usuario->id)->get();
                        $usuario['doc_id'] = $ci[0]->documento_identidad;
                    }
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

    //    Funcion para ordenar por apellido arreglos de objetos
    public function cmp($a, $b) {
        return strcmp($a[0]->id, $b[0]->id);
    }

    /**
     * Permite la busqueda segun los paraemetros dados por el usuario.
     *
     * @return Retorna la vista de la lista de usuarios deseados.
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
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['roles'] = Role::all()->lists('display_name', 'id');
                $param = Input::get('parametro');
                $data['busq_'] = true;

                if($param == '0'){
                    $data['usuarios'] = User::orderBy('id')->get();
                    $data['errores'] = '';
                    $data['roles'] = Role::all()->lists('display_name', 'id');
                    $data['busq'] = false;

                    foreach ($data['usuarios'] as $usuario) { //se asocian los roles a cada usuario
                        $usuario['roles'] = $usuario->roles()->get();
                        $ci = Participante::where('id_usuario', '=', $usuario->id)->get();
                        if($ci->count()){
                            $usuario['doc_id'] = $ci[0]->documento_identidad;
                        }else{
                            $ci = Profesor::where('id_usuario', '=', $usuario->id)->get();
                            $usuario['doc_id'] = $ci[0]->documento_identidad;
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('usuarios.usuarios', $data);
                }

                if ($param != 'rol'){
                    if (empty(Input::get('busqueda'))) {
                        $data['usuarios'] = User::orderBy('id')->get();
                        $data['errores'] = '';
                        $data['roles'] = Role::all()->lists('display_name', 'id');
                        $data['busq'] = false;

                        foreach ($data['usuarios'] as $usuario) { //se asocian los roles a cada usuario
                            $usuario['roles'] = $usuario->roles()->get();
                            $ci = Participante::where('id_usuario', '=', $usuario->id)->get();
                            if($ci->count()){
                                $usuario['doc_id'] = $ci[0]->documento_identidad;
                            }else{
                                $ci = Profesor::where('id_usuario', '=', $usuario->id)->get();
                                $usuario['doc_id'] = $ci[0]->documento_identidad;
                            }
                        }

                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('usuarios.usuarios', $data);
                    }else{
                        $busq = Input::get('busqueda');
                    }
                }else{
                    $busq = Input::get('busqu');
                }

                $data['errores'] = '';
                $usuarios = [];
                if(($param != 'documento_identidad') && ($param != 'rol')){
                    $data['busq'] = false;
                    $data['usuarios'] = User::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                    foreach ($data['usuarios'] as $usuario) { //se asocian los roles a cada usuario
                        $usuario['roles'] = $usuario->roles()->get();
                        $ci = Participante::where('id_usuario', '=', $usuario->id)->get();
                        if($ci->count()){
                            $usuario['doc_id'] = $ci[0]->documento_identidad;
                        }else{
                            $ci = Profesor::where('id_usuario', '=', $usuario->id)->get();
                            $usuario['doc_id'] = $ci[0]->documento_identidad;
                        }
                    }
                }elseif($param == 'documento_identidad'){
                    $data['busq'] = true;
                    $data['usuarios'] = [];
                    $parts = Participante::where('documento_identidad' ,'like', '%'.$busq.'%')->get();//->select('id_usuario');
                    $profs = Profesor::where('documento_identidad' ,'like', '%'.$busq.'%')->get();
                    if($parts->count()){
                        foreach ($parts as $index => $part){
                            $usuarios[$index] = User::where('id', '=', $part->id_usuario)->get();
                        }
                    }
                    if($profs->count()){
                        $index2 = count($usuarios);
                        foreach ($profs as $prof){
                            $usuarios[$index2] = User::where('id', '=', $prof->id_usuario)->get();
                            $index2 ++;
                        }
                    }
                    usort($usuarios, array($this, "cmp")); //Ordenar por id
                    $data['usuarios'] = $usuarios;
                    foreach ($data['usuarios'] as $usuario) { //se asocian los roles a cada usuario
                        $usuario['roles'] = $usuario[0]->roles()->get();
                        $ci = Participante::where('id_usuario', '=', $usuario->id)->get();
                        if($ci->count()){
                            $usuario['doc_id'] = $ci[0]->documento_identidad;
                        }else{
                            $ci = Profesor::where('id_usuario', '=', $usuario->id)->get();
                            $usuario['doc_id'] = $ci[0]->documento_identidad;
                        }
                    }
                }elseif($param == 'rol'){
                    $data['busq'] = true;
                    $rol = Role::where('id', '=', $busq)->get();
                    if($rol->count()){
                        $users = DB::table('role_user')->where('role_id', '=', $rol[0]->id)->get();
                        $users = array($users);
                        if($users[0] != null) {
                            foreach ($users[0] as $index => $user) {
                                $usuarios[$index] = User::where('id', '=', $user->user_id)->get();
                            }
                        }
                    }
                    if($usuarios != null) {
                        $usuarios = array($usuarios);
                        $data['usuarios'] = $usuarios[0];
                        foreach ($data['usuarios'] as $usuario) { //se asocian los roles a cada usuario
                            $usuario['roles'] = $usuario[0]->roles()->get();
                            $ci = Participante::where('id_usuario', '=', $usuario[0]->id)->get();
                            if($ci->count()){
                                $usuario['doc_id'] = $ci[0]->documento_identidad;
                            }else{
                                $ci = Profesor::where('id_usuario', '=', $usuario[0]->id)->get();
                                $usuario['doc_id'] = $ci[0]->documento_identidad;
                            }
                        }
                    }else{
                        $data['usuarios'] = '';
                    }
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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción

                //se eliminan los datos guardados en sesion anteriormente
                Session::forget('nombre');
                Session::forget('apellido');
                Session::forget('email');
                Session::forget('documento_identidad');
                Session::forget('telefono');
                Session::forget('celular');
                Session::forget('email_alternativo');
                Session::forget('twitter');
                Session::forget('ocupacion');
                Session::forget('titulo');
                Session::forget('univ');

                $data['roles'] = Role::all()->lists('display_name', 'id');
                $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                $data['estados'] = Estado::all()->lists('estado','id_estado');
                $data['errores'] = '';
                $data['es_participante'] = false;

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
     * @param   UsuarioRequest    $request (Se validan los campos ingresados por el usuario mediante el Request antes guardarlos)
     *
     * @return Retorna la vista de la lista de usuarios con el nuevo usuario agregado.
     */
	public function store(UsuarioRequest $request)	{

        try {

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['es_participante'] = false;
                $data['errores'] = '';
                $dir = "";
                //dd("pais: ".Input::get('id_pais')." ciudad: ".Input::get('ciudad')."  Municipio: ".Input::get('municipio')."  parr: ".Input::get('parroquia'));

                $create = User::create([ //  Se crea el usuario en la tabla Users
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'email' => $request->email,
                    'foto' => 'foto_participante.png',
                    'password' => bcrypt($request->password),
                ]);

                $usuario = User::find($create->id);
                $roles = $request->id_rol; // se obtienen los roles que le haya seleccionado el usuario en el formulario
                $create2 = 0;

                if (($request->es_participante) == 'si') {  // Se verifica si el usuario es del tipo Perticipante y se crea en la tabla Participantes
                    $pais = Input::get('id_pais');
                    if ($pais == 231) {
                        $estado = Input::get('id_est');
                        $ciudad = Input::get('ciudad');
                        $municipio = Input::get('municipio');
                        $parroquia = Input::get('parroquia');
                        if (($estado  == 0) || ($ciudad == 0) || ($municipio == 0) || ($parroquia == 0)) {
                            DB::table('users')->where('id', '=', $usuario->id)->delete();
                            $data['errores'] = "Debe completar todos los datos de la direecion (Estado, Ciudad, Municipio y Parroquia)";
                            $data['roles'] = Role::all()->lists('display_name', 'id');
                            $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                            $data['estados'] = Estado::all()->lists('estado','id_estado');
                            $data['es_participante'] = true;
                            // Se guardan los datos ingresados por el usuario en sesion pra utilizarlos en caso de que se redirija
                            // al usuari al formulario por algún error y no se pierdan los datos ingresados
                            Session::set('nombre', $request->nombre);
                            Session::set('apellido', $request->apellido);
                            Session::set('email', $request->email);
                            Session::set('documento_identidad', $request->documento_identidad);
                            Session::set('telefono', $request->telefono);
                            Session::set('celular', $request->celular);
                            Session::set('email_alternativo', $request->email_alternativo);
                            Session::set('twitter', $request->twitter);
                            Session::set('ocupacion', $request->ocupacion);
                            Session::set('titulo', $request->titulo);
                            Session::set('univ', $request->univ);

                            return view('usuarios.crear', $data);
                        }
                        $dir = $pais.'-'.$estado.'-'.$ciudad.'-'.$municipio.'-'.$parroquia;
                        
                    }elseif ($pais == 0) {
                        DB::table('users')->where('id', '=', $usuario->id)->delete();
                        $data['errores'] = "Debe completar el campo pais";
                        $data['roles'] = Role::all()->lists('display_name', 'id');
                        $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                        $data['estados'] = Estado::all()->lists('estado','id_estado');
                        $data['es_participante'] = true;
                        // Se guardan los datos ingresados por el usuario en sesion pra utilizarlos en caso de que se redirija
                        // al usuari al formulario por algún error y no se pierdan los datos ingresados
                        Session::set('nombre', $request->nombre);
                        Session::set('apellido', $request->apellido);
                        Session::set('email', $request->email);
                        Session::set('documento_identidad', $request->documento_identidad);
                        Session::set('telefono', $request->telefono);
                        Session::set('celular', $request->celular);
                        Session::set('email_alternativo', $request->email_alternativo);
                        Session::set('twitter', $request->twitter);
                        Session::set('ocupacion', $request->ocupacion);
                        Session::set('titulo', $request->titulo);
                        Session::set('univ', $request->univ);

                        return view('usuarios.crear', $data);
                    }else{
                        $dir = $pais;
                    }

                    //dd("direccion: ".$dir);
                    $create2 = Participante::findOrNew($request->id);
                    $create2->id_usuario = $usuario->id;
                    $create2->nombre = $request->nombre;
                    $create2->apellido = $request->apellido;
                    $create2->documento_identidad = $request->documento_identidad;
                    $create2->telefono = $request->telefono;
                    $create2->direccion = $dir;
                    $create2->celular = $request->celular;
                    $create2->correo_alternativo = $request->email_alternativo;
                    $create2->twitter = Input::get('twitter');
                    $create2->ocupacion = Input::get('ocupacion');
                    $create2->titulo_pregrado = Input::get('titulo');
                    $create2->universidad = Input::get('univ');

                } elseif (($request->es_participante) == 'no') {    //  Si no es Perticipante entonces es Profesor
                    // Se verifica que el usuario haya seleccionado que roles tendrá el usuario que se está creando

                    if (empty(Input::get('id_rol'))) {  // Si no ha seleccionado ningún rol, se redirige al formulario

                        DB::table('users')->where('id', '=', $usuario->id)->delete();
                        $data['errores'] = "Debe seleccionar un rol";
                        $data['roles'] = Role::all()->lists('display_name', 'id');

                        // Se guardan los datos ingresados por el usuario en sesion pra utilizarlos en caso de que se redirija
                        // al usuari al formulario por algún error y no se pierdan los datos ingresados
                        Session::set('nombre', $request->nombre);
                        Session::set('apellido', $request->apellido);
                        Session::set('email', $request->email);
                        Session::set('documento_identidad', $request->documento_identidad);
                        Session::set('telefono', $request->telefono);
                        Session::set('celular', $request->celular);

                        return view('usuarios.crear', $data);

                    } else {    // Si se seleccionaron los roles se crea el nuevo usuario en la tabla Profesores

                        $create2 = Profesor::create([
                            'id_usuario' => $usuario->id,
                            'nombre' => $request->nombre,
                            'apellido' => $request->apellido,
                            'documento_identidad' => $request->documento_identidad,
                            'telefono' => $request->telefono,
                            'celular' => $request->celular
                        ]);
                    }
                }

                // Se verifica que se haya creado el de forma correcta
                if ($create->save()) {
                    if ($create2->save()) {
                        //Se guardan los roles asociados al usuario en la tabla User_role y se redirige a la lista de usuarios con el nuevo usuario agregado
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
                    } else {    // Si el usuario no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        DB::table('users')->where('id', '=', $usuario->id)->delete();
                        return view('usuarios.crear');
                    }
                } else {    // Si el usuario no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
                    Session::set('error', 'Ha ocur rido un error inesperado');
                    return view('usuarios.crear');
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
     * Se muestra el formulario de edicion de usuario si posee los permisos necesarios.
     *
     * @param  int  $id (id del usuario seleccionado)
     *
     * @return Retorna vista del formulario para el editar el usuario deseado.
     */
	public function edit($id) {
		try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_usuarios')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['estado'] = '';
                $data['ciudad'] = '';
                $data['municipio'] = '';
                $data['parroquia'] = '';
                $data['es_VE'] = false;

                $data['errores'] = '';
                $data['es_participante'] = false;
                $usuario = User::find($id);
                $data['usuarios'] = $usuario;    //Se obtienen los datos del usuario que se desea editar
                $userRoles = $data['usuarios']->roles()->get(); // Se obtienen los roles del usuario que se desea editar
                $data['rol'] = $userRoles;
                $data['roles'] = Role::where('name', '!=', 'participante')->lists('display_name', 'id');
                /*$data['paises'] = Pais::all()->lists('pais', 'id');
                $data['estados'] = Estado::all()->lists('estado','id_estado');
                $data['ciudades'] = Ciudad::all()->lists('ciudad', 'id_ciudad');
                $data['municipios'] = Municipio::all()->lists('municipio','id_municipio');
                $data['parroquias'] = Parroquia::all()->lists('parroquia', 'id_parroquia');*/

                foreach ($userRoles as $role) { //  Se verifica el rol del usuario que se desea editar
                // (si es Participante o Profesor) y se obtienen su datos
                    if (($role->name) == 'participante') {
                        $data['es_participante'] = true;
                        $data['datos_usuario'] = DB::table('participantes')->where('id_usuario', '=', $usuario->id)->first();
                        /*$direccion = $data['datos_usuario']->direccion;
                        $dir = explode("-", $direccion);
                        //$data['paiss'] = $dir[0];
                        $es_ve = strpos($direccion, '-');
                        if ($es_ve) {
                            $data['es_VE'] = true;
                            $data['estado'] = $dir[1];
                            $data['ciudad'] = $dir[2];
                            $data['municipio'] = $dir[3];
                            $data['parroquia'] = $dir[4];
                        }*/
                        
                    } else {
                        $data['datos_usuario'] = DB::table('profesores')->where('id_usuario', '=', $usuario->id)->first();

                        $arr = [];
                        $usuario_rol = array($userRoles);
//                        dd($usuario_rol[0]);

                        foreach ($data['roles'] as $index => $rol) {
                            $arr[$index] = false;
                        }
                        //dd($usuario_rol[0]);
                        foreach ($usuario_rol[0] as $index => $rol) {
                            $arr[$rol->id] = true;
                        }
                        $data['rols'] = $arr;
                    }
                    break;
                }
//                dd($data['es_participante']);
                //  Se retorna el fomulario de edición con los datos del usuario
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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $usuario = User::find($id); // Se obtienen los datos del usuario que se desea editar
                $userRoles = $usuario->roles()->get();  // Se obtienen los roles del usuario
                $es_participante = false;

                foreach ($userRoles as $role) {     // Se verifica si el usuario posee el rol de participante
                    if (($role->name) == 'participante') {
                        $es_participante = true;
                    }
                    break;
                }

                $email = $request->email;
                // Se verifica si el correo ingresado es igual al anterior y si no lo es se verifica que no
                // conicida con los de las base de datos ya que debe ser único
                if (!($email == $usuario->email)) {

                    $existe = DB::table('users')->where('email', '=', $email)->first();

                    // Si el correo conicide con alguno de la base de datos se redirige al usuario al formulario de
                    // edición indicandole el error
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

                $roles = Input::get('id_rol');

                if ($es_participante) {
                    $tipo_usuario = Participante::find(1)->where('id_usuario', '=', $id)->first();

                    // Se editan los datos del usuario deseado de la tabla Users con los datos ingresados en el formulario
                    $usuario->nombre = $request->nombre;
                    $usuario->apellido = $request->apellido;
                    $usuario->email = $email;
                    $usuario->password = $password;
                    $usuario->save();   // Se guardan los nuevos datos en la tabla Users


                    // Se editan los datos del usuario deseado de la tabla Participentes con los datos ingresados en el formulario
                    $tipo_usuario->nombre = $request->nombre;
                    $tipo_usuario->apellido = $request->apellido;
                    $tipo_usuario->documento_identidad = $request->documento_identidad;
                    $tipo_usuario->telefono = $request->telefono;
                    $tipo_usuario->celular = $request->celular;
                    $tipo_usuario->correo_alternativo = $request->correo_alternativo;
                    $tipo_usuario->twitter = Input::get('twitter');
                    $tipo_usuario->ocupacion = Input::get('ocupacion');
                    $tipo_usuario->titulo_pregrado = Input::get('titulo');
                    $tipo_usuario->universidad = Input::get('univ');

                    $tipo_usuario->save(); // Se guardan los nuevos datos en la tabla Participentes

                } else {    // Si el usuario a editar no es Participante

                    // Se verifica que el usuario haya seleccionado algún rol, si no seleccionó ninguno
                    // se redirige al formulario indicandole el error
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

                    } else {    // Si se completaron todos los campos necesarios se guardan los datos en la tabla Profesores
                        $tipo_usuario = Profesor::find(1)->where('id_usuario', '=', $id)->first();

                        // Se editan los datos del usuario deseado de la tabla Users con los datos ingresados en el formulario
                        $usuario->nombre = $request->nombre;
                        $usuario->apellido = $request->apellido;
                        $usuario->email = $email;
                        $usuario->password = $password;
                        $usuario->save();

                        $tipo_usuario->nombre = $request->nombre;
                        $tipo_usuario->apellido = $request->apellido;
                        $tipo_usuario->documento_identidad = $request->documento_identidad;
                        $tipo_usuario->telefono = $request->telefono;
                        $tipo_usuario->celular = $request->celular;

                        $tipo_usuario->save();
                    }
                }

                //  Si se actualizaron con exito los datos del usuario, se actualizan los roles del usuario.
                if ($usuario->save()) {
                    if ($tipo_usuario->save()) {
                        if (!$es_participante) {    // Si es Participante no se cambia nada, sino se actualizan los roles.
                            DB::table('role_user')->where('user_id', '=', $id)->delete();
                            foreach ($roles as $rol) {
                                $role = DB::table('roles')->where('display_name', '=', $rol)->first();
                                $usuario->attachRole($role->id);
                            }
                        }
                        return redirect('/usuarios');
                    } else {    // Si el usuario no se ha actualizo con exito en la tabla Participantes o Profesores se
                    // redirige al formulario  y se le indica al usuario el error
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        DB::table('users')->where('id', '=', $usuario->id)->delete();
                        return view('usuarios.edit');
                    }
                // Si el usuario no se ha actualizo con exito en la tabla Users se redirige al
                // formulario y se le indica al usuario el error
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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('eliminar_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción

                // Se obtienen los datos del usuario que se desea eliminar al igual que los roles que posee
                $usuario = User::find($id);
                $data['busq_'] = false;
                $data['busq'] = false;
//                dd($usuario->id);
                $roles = $usuario->roles()->get();
                $data['roles'] = Role::all()->lists('display_name', 'id');

                // Se verifica los roles que posee el usuario que se desea eliminar
                foreach ($roles as $role) {
                    // Si el usuario que se desea eliminar es Administrador, no se puede eliminar
                    if (($role->name) == 'admin') {
                        $data['errores'] = "El usuario Administrador no puede ser eliminado";
                        $data['usuarios'] = User::all();
                        foreach ($data['usuarios'] as $usuario) {
                            $usuario['rol'] = $usuario->roles()->first();

                        }
                        return view('usuarios.usuarios', $data);
                    }elseif (($role->name) == 'participante') { // Si el usuario que se desea eliminar es Participante,
                    // se elimina y todas sus referencias
//                        $participante =DB::table('participantes')->where('id_usuario', '=', $usuario->id)->get();
                        $participante = Participante::find(1)->where('id_usuario', '=', $usuario->id)->first();
                        DB::table('participante_cursos')->where('id_participante', '=', $participante->id)->delete();
                        DB::table('participante_webinars')->where('id_participante', '=', $participante->id)->delete();
                        DB::table('participantes')->where('id_usuario', '=', $usuario->id)->delete();
                        User::destroy($id);
                    } else {     // Si el usuario que se desea eliminar es Profesor o Coordinador, se elimina y todas sus referencias
//                        $profesor = DB::table('profesores')->where('id_usuario', '=', $usuario->id)->get();
                        $profesor = Profesor::find(1)->where('id_usuario', '=', $usuario->id)->first();
                        DB::table('profesor_cursos')->where('id_profesor', '=', $profesor->id)->delete();
                        DB::table('profesor_webinars')->where('id_profesor', '=', $profesor->id)->delete();
                        DB::table('profesores')->where('id_usuario', '=', $usuario->id)->delete();
                        User::destroy($id);
                    }
                    break;
                }

                DB::table('role_user')->where('user_id', '=', $id)->delete(); // Se eliminan los roles asociados a al usuario que se eliminó

                // Se redirige al usuario a la lista de usuarios actualizada
                $data['usuarios'] = User::all();
                $data['errores'] = "";
                foreach ($data['usuarios'] as $usuario) {
                    $usuario['rol'] = $usuario->roles()->first();

                }
                Session::set('mensaje', 'Usuario eliminado con éxito');
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
