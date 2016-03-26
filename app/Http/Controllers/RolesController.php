<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\RolRequest;
use App\Http\Requests\RolEditarRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller {

	/**
     * Muestra la vista de la lista de roles si posee los permisos necesarios.
     *
     * @return Retorna la vista de la lista de roles.
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

            if($usuario_actual->can('ver_roles')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq'] = false;
                $data['busq_'] = false;
                $data['roles'] = Role::orderBy('name')->get();   // Se obtienen todos los roles
                foreach ($data['roles'] as $rol) {
                    $rol['permisos'] = $rol->perms()->get();    //Se obtienen los permisos asociados a cada rol
                }
                return view('roles.roles', $data);  // Se muestra la lista de roles

            }else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

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
     * @return Retorna la vista de la lista de webinars activos deseados.
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
            if($usuario_actual->can('ver_webinars')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['roles'] = '';
                $data['errores'] = '';
                $data['busq'] = true;
                $data['busq_'] = true;
                $param = Input::get('parametro');
                if($param == '0'){
                    $data['roles'] = Role::orderBy('name')->get();
                    foreach ($data['roles'] as $rol) {
                        $rol['permisos'] = $rol->perms()->get();    //Se obtienen los permisos asociados a cada rol
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('roles.roles', $data);
                }
                if ($param == 'name'){
                    if (empty(Input::get('busqueda'))) {
                        $data['roles'] = Role::orderBy('name')->get();
                        foreach ($data['roles'] as $rol) {
                            $rol['permisos'] = $rol->perms()->get();    //Se obtienen los permisos asociados a cada rol
                        }
                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('roles.roles', $data);
                    }else{
                        $busq = Input::get('busqueda');
                    }
                }
                if(($param == 'name')){
                    $data['roles'] = Role::where($param, 'ilike', '%'.$busq.'%')
                        ->orderBy('name')->get();
                    foreach ($data['roles'] as $rol) {
                        $rol['permisos'] = $rol->perms()->get();    //Se obtienen los permisos asociados a cada rol
                    }
                }

                return view('roles.roles', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }


    /**
     * Muestra el formulario para crear un nuevo rol si posee los permisos necesarios.
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

            if($usuario_actual->can('crear_roles')) { // Si el usuario posee los permisos necesarios continua con la acción

                // Se eliminan los datos guardados en sesion anteriormente
                Session::forget('nombre');
                Session::forget('descripcion');

                // Se obtienen todos los permisos guardados en la base datos
                $data['permisos'] = Permission::all()->lists('display_name','id');
                $data['errores'] = '';

                return view ('roles.crear', $data); // Se muestra el formulario

            }else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

    /**
     * Guarda el nuevo rol si el usuario posee los permisos necesarios.
     *
     * @param   RolRequest    $request (Se validan los campos ingresados por el usuario mediante el Request antes guardarlos )
     *
     * @return Retorna la vista de la lista de roles con el nuevo rol agregado.
     */
	public function store(RolRequest $request)
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

            if($usuario_actual->can('crear_roles')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $permisos = $request->permisos; // Permisos seleccionados en el formulario
    //            dd($permisos);

                // Se verifica si el usuario selecciono por lo menos un permiso que será asociado al nuevo rol
                if ( empty(Input::get( 'permisos' )) ) {    //Si no selccionó ninguno, se redirige al formulario indicandole el error
    //                    dd("fallo modalidad");
                    $data['errores'] = "Debe seleccionar al menos un (1) permiso";
                    $data['permisos'] = Permission::all()->lists('display_name','id');

                    Session::set('nombre', $request->name);
                    Session::set('descripcion', $request->descripcion);

                    return view('roles.crear', $data);

                }else{  //Si todos los campos están completos se crea el nuevo rol

                    $create = new Role();//::findOrNew($request->id);
                    $create->name = $request->name;
                    $create->display_name = $request->name;
                    $create->description = $request->descripcion;
                    $create->save();
                }


                // Se verifica que se haya creado el rol correctamente
                if($create->save()) {
                    foreach ($permisos as $permiso) {   // Se asignan los permisos al nuevo rol
                        $role = Role::where('name', '=', $create->name)->first();
    //                    dd($role);
                        $perms = Permission::where('display_name', '=', $permiso)->first();
    //                    DB::table('permissions')->where('display_name', '=', $permiso)->first();

    //                    dd($role);
                        $role->attachPermission($perms);
                    }
                    return redirect('/roles');

                }else{  // Si el rol no se ha creado bien se redirige al formulario de creación y se le indica al usuario
                    Session::set('error','Ha ocurrido un error inesperado');
                    return view('roles.crear');
                }
            }else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
        }
        catch (Exception $e)
        {
            return view('errors.error')->with('error',$e->getMessage());
        }
	}


    /**
     * Se muestra el formulario de edicion de rol si posee los permisos necesarios.
     *
     * @param  int  $id (id del rol seleccionado)
     *
     * @return Retorna vista del formulario para el editar el rol deseado.
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

            if($usuario_actual->can('editar_roles')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['roles'] = Role::find($id);   // Se ontienen los datos del rol que se desea editar
                $data['permisos'] = Permission::all()->lists('display_name','id');  // Se obtienen todos los permisos guardados en base de datos
                $permisos = $data['roles']->perms()->get(); 
                $arr = [];
                $permisos = array($permisos);
                //dd($permisos);

                foreach ($data['permisos'] as $index => $permiso) {
                    $arr[$index] = false;
                }
                //dd(count($permisos[0]));
                foreach ($permisos[0] as $index => $perm) {
                    $arr[$perm->id] = true;
                }
                $data['perms'] = $arr;
                
                return view ('roles.editar', $data);

            }else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

    /**
     * Actualiza los datos del rol seleccionado si posee los permisos necesarios
     *
     * @param  int  $id (id del rol seleccionado)
     * @param  RolRequest  $request (Se validan los campos ingresados por el usuario mediante el Request antes guardarlos)
     *
     * @return Retorna la lista de roles con los datos actualizados.
     */
	public function update(RolEditarRequest $request, $id)
	{
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_roles')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $roles = Role::findOrFail($id);   // Se obtienen los datos del rol seleccionado
                $permisos = $request->permisos;     // Se obtienen los permisos seleccionados por el usuario en el formulario

                $rol = $request->name;
                if (!($rol == $roles->name)) {

                    $existe = DB::table('users')->where('name', '=', $rol)->first();

                    if ($existe) {
                        $data['errores'] = "Ya existe un Rol con ese nombre, ingrese uno diferente";
                        $data['permisos'] = Permission::all()->lists('display_name','id');
                        $data['roles'] = Role::findOrFail($id);

                        return view('roles.editar', $data);
                    }
                }
                // Se verifica si el usuario selecciono por lo menos un permiso que será asociado al rol
                if ( empty(Input::get( 'permisos' )) ) {   //Si no selccionó ninguno, se redirige al formulario indicandole el error
    //                    dd("fallo modalidad");
                    $data['errores'] = "Debe seleccionar al menos un (1) permiso";
                    $data['permisos'] = Permission::all()->lists('display_name','id');
                    $data['roles'] = Role::findOrFail($id);

                    return view('roles.editar', $data);

                }else{      //Si todos los campos están completos se crea el nuevo rol

                    $roles->name = $request->name;
                    $roles->display_name = $request->name;
                    $roles->description = $request->descripcion;


                }

                // Se verifica que se haya creado el rol correctamente
                if($roles->save()) {
                    DB::table('permission_role')->where('role_id', '=', $id)->delete(); //Se borren los roles anteriores y a continuación se guardan los nuevos
                    foreach ($permisos as $permiso) {
                        $role = Role::where('name', '=', $roles->name)->first();
                        $perms = Permission::where('display_name', '=', $permiso)->first();
                        $role->attachPermission($perms);
                    }

                    return redirect('/roles');

                }else{      // Si el rol no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
                    Session::set('error','Ha ocurrido un error inesperado');
                    return view('roles.editar');
                }
            }else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
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

            if($usuario_actual->can('eliminar_roles')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $rol = Role::find($id);
//                $permisos = $rol->perms()->get();
                $data['busq'] = false;
                $data['busq_'] = false;

                if (($rol->name) == 'admin') {
                    $data['errores'] = "El rol Administrador no puede ser eliminado";

                    return view('roles.roles', $data);

                } elseif (($rol->name) == 'coordinador') {
                    $data['errores'] = "El rol Coordinador no puede ser eliminado";

                    return view('roles.roles', $data);

                } elseif (($rol->name) == 'participante') {
                    $data['errores'] = "El rol Participante no puede ser eliminado";

                    return view('roles.roles', $data);

                } elseif (($rol->name) == 'profesor') {
                    $data['errores'] = "El rol Profesor no puede ser eliminado";

                    return view('roles.roles', $data);
                }

                DB::table('permission_role')->where('role_id', '=', $id)->delete();
                Role::destroy($id);

                $data['roles'] = Role::all();
                $data['errores'] = '';

                foreach ($data['roles'] as $rol) {
                    $rol['permisos'] = $rol->perms()->get();
                }

                return view('roles.roles', $data);

            }else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

}
