<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		try{
            $data['errores'] = '';
			$data['roles'] = Role::all();

			foreach($data['roles'] as $rol){
                $rol['permisos'] = $rol->perms()->get();
			}

			return view('roles.roles', $data);

		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        try{

            Session::forget('nombre');
            Session::forget('permisos');
//            Session::forget('fecha_inicio');

            $data['permisos'] = Permission::all()->lists('display_name','id');
            $data['errores'] = '';

            return view ('roles.crear', $data);
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        try
        {

            $data['errores'] = '';
            $permisos = $request->permisos;
//            dd($permisos);

            if ( empty(Input::get( 'permisos' )) ) {
//                    dd("fallo modalidad");
                $data['errores'] = "Debe seleccionar al menos un (1) permiso";
                $data['permisos'] = Permission::all()->lists('display_name','id');

                Session::set('nombre', $request->nombre);
                Session::set('descripcion', $request->descripcion);

                return view('roles.crear', $data);

            }else{

                $create = Role::findOrNew($request->id);
                $create->name = $request->nombre;
                $create->display_name = $request->nombre;
                $create->description = $request->descripcion;
            }
//            attachPermission

            if($create->save()) {
                foreach ($permisos as $permiso) {
                    $role = Role::where('name', '=', $create->name)->first();
//                    dd($role);
                    $perms = Permission::where('display_name', '=', $permiso)->first();
//                    DB::table('permissions')->where('display_name', '=', $permiso)->first();

//                    dd($role);
                    $role->attachPermission($perms);
                }
                return redirect('/roles');
            }else{
                Session::set('error','Ha ocurrido un error inesperado');
                return view('roles.crear');
            }

        }
        catch (Exception $e)
        {
            return view('errors.error')->with('error',$e->getMessage());
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        try{
//            dd($id );
            $data['errores'] = '';
            $data['roles'] = Role::find($id);
            $data['permisos'] = Permission::all()->lists('display_name','id');

            return view ('roles.editar', $data);
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
        try{

            $data['errores'] = '';
            $roles = Role::find($id);
            $permisos = $request->permisos;

            if ( empty(Input::get( 'permisos' )) ) {
//                    dd("fallo modalidad");
                $data['errores'] = "Debe seleccionar al menos un (1) permiso";
                $data['permisos'] = Permission::all()->lists('display_name','id');

                return view('roles.crear', $data);

            }else{

                $roles->name = $request->nombre;
                $roles->display_name = $request->nombre;
                $roles->description = $request->descripcion;

            }

            if($roles->save()) {
                DB::table('permission_role')->where('role_id', '=', $id)->delete();
                foreach ($permisos as $permiso) {
                    $role = Role::where('name', '=', $roles->name)->first();
                    $perms = Permission::where('display_name', '=', $permiso)->first();
                    $role->attachPermission($perms);
                }

                return redirect('/roles');

            }else{
                Session::set('error','Ha ocurrido un error inesperado');
                return view('roles.editar');
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

            $rol = Role::find($id);
            $permisos = $rol->perms()->get();

//           dd($roles[0]->name );
            if (($rol->name) == 'admin'){
                $data['errores'] = "El rol Administrador no puede ser eliminado";

                return view ('roles.roles', $data);

            }elseif(($rol->name) == 'coordinador'){
                $data['errores'] = "El rol Coordinador no puede ser eliminado";

                return view ('roles.roles', $data);

            }elseif(($rol->name) == 'participante') {
                $data['errores'] = "El rol Participante no puede ser eliminado";

                return view('roles.roles', $data);

            }elseif(($rol->name) == 'profesor') {
                $data['errores'] = "El rol Profesor no puede ser eliminado";

                return view('roles.roles', $data);
            }

            DB::table('permission_role')->where('role_id', '=', $id)->delete();
            Role::destroy($id);

            $data['roles'] = Role::all();
            $data['errores']='';

            foreach($data['roles'] as $rol){
                $rol['permisos'] = $rol->perms()->get();
            }

            return view ('roles.roles', $data);
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

}
