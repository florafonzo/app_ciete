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
use Illuminate\Support\Facades\Input;
use Illuminate\Http\RedirectResponse;
use DB;

use Illuminate\Http\Request;

class UsuariosController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		try{
			$data['usuarios'] = User::all();
//			$data['participantes'] = User::all();
//            $data['profes'] = User::all();
			
			foreach($data['usuarios'] as $usuario){
				$usuario['roles'] = $usuario->roles()->get();
//                dd($usuario['rol']);
			}
			return view('usuarios.usuarios', $data);

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
	public function create() {
		try{
	        $data['roles'] = Role::all()->lists('display_name','id');

	        return view ('usuarios.crear', $data);
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
	public function store(UsuarioRequest $request)	{
//
//        $roles = $request->id_rol;
//        $rols=0;
//        foreach($roles as $rol) {
//            $rols = $rols + 1;
//        }
//        dd($rols);
//        dd($request->es_participante);

        try {
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
            }else{
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

            } elseif (($request->es_participante) == 'no'){

//                $validation = Validator::make(
//                    array(
//                        'id_rol' => Input::get( 'id_rol' ),
//                    ),
//                    array(
//                        'id_rol' => array( 'required', 'id_rol' ),
//                    )
//                );

                if ( empty(Input::get( 'id_rol' )) ) {
//                    dd("fallo roless");
                    DB::table('users')->where('id', '=', $usuario->id)->delete();
                    $errors = "Debe seleccionar un rol";
                    $data['roles'] = Role::all()->lists('display_name','id');

                    return view('usuarios.crear', $data)->with('error',$errors);
                }else{

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
                        $role = DB::table('roles')->where('name','=', 'participante')->first();
                        $usuario->attachRole($role->id);
                    }elseif (($request->es_participante) == 'no') {
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

        }
        catch (Exception $e)
        {
            return view('errors.error')->with('error',$e->getMessage());
        }

//        $input = Input::all();
//        $input['password'] = Hash::make($input['password']);
//
//        $user = User::create($input);
//        $user->attachRole(Role::find(Input::get('rol')));
//        return Redirect::route('users.index');
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
	public function edit($id) {
		try{
            $data['es_participante'] = false;
            $usuario = User::find($id);
	        $data['usuarios'] = $usuario;
	        $userRoles = $data['usuarios']->roles()->get();
	        $data['rol'] = $userRoles;
	        $data['roles'] = Role::all()->lists('display_name','id');

            foreach($userRoles as $role){
                if(($role->name) == 'participante'){
                    $data['es_participante'] = true;
                    $data['datos_usuario'] = DB::table('participantes')->where('id_usuario', '=', $usuario->id)->first();
                }else{
                    $data['datos_usuario'] = DB::table('profesores')->where('id_usuario', '=', $usuario->id)->first();
                }
//                dd($data['datos_usuario']);
                break;
            }

	        return view ('usuarios.edit', $data);
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
	public function update(UsuarioEditarRequest $request, $id) {

		try {

			$usuario = User::find($id);
            $userRoles = $usuario->roles()->get();
            $es_participante = false;

            foreach($userRoles as $role){
                if(($role->name) == 'participante') {
                    $es_participante = true;
                }
                break;
            }


            $email = Input::get('email');
            
            if (!($email == $usuario->email)){
//                dd("Email No es IGUAL");
            	$email = $request -> email;
            }
            
            $password = bcrypt($request -> password);

            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->email = $email;
            $usuario->password = $password;


            $roles = Input::get('id_rol');

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
            }else{
                $imagen = '';
            }

            if ($es_participante) {
                $usuario->save();

                $tipo_usuario = DB::table('participantes')->where('id_usuario', '=', $id)->first();

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

            }else{

////                $validator = Validator::make(
////                    ['name' => 'Dayle'],
////                    ['name' => 'required|min:5']
////                );
//                $validation = Validator::make(
//                    array(
//                        'id_rol' => Input::get( 'id_rol' ),
//                    ),
//                    array(
//                        'id_rol' => array( 'required', 'id_rol' ),
//                    )
//                );

                if ( empty(Input::get('id_rol')) ) {
//                    dd('Rolesss vacioss');
                    $errors = "Debe seleccionar un Rol";

                    $data['es_participante'] = false;
                    $usuario = User::find($id);
                    $data['usuarios'] = $usuario;
                    $userRoles = $data['usuarios']->roles()->get();
                    $data['rol'] = $userRoles;
                    $data['roles'] = Role::all()->lists('display_name','id');

                    foreach($userRoles as $role){
                        if(($role->name) == 'participante'){
                            $data['es_participante'] = true;
                            $data['datos_usuario'] = DB::table('participantes')->where('id_usuario', '=', $usuario->id)->first();
                        }else{
                            $data['datos_usuario'] = DB::table('profesores')->where('id_usuario', '=', $usuario->id)->first();
                        }
                        break;
                    }

                    return view('usuarios.edit', $data)->with('errors',$errors);
                }else {

                    $usuario->save();

                    $tipo_usuario = DB::table('profesores')->where('id_usuario', '=', $id)->first();

                    $tipo_usuario->nombre = $request->nombre;
                    $tipo_usuario->apellido = $request->apellido;
                    $tipo_usuario->documento_identidad = $request->documento_identidad;
                    $tipo_usuario->telefono = $request->telefono;
                    $tipo_usuario->foto = $imagen;
                    $tipo_usuario->celular = $request->celular;

                    $tipo_usuario->save();
                }
            }

//                if ($usuario->save()) {
//                    $user = User::find($id);
//                    $user->roles()->detach();
//                    $user->attachRole($request->rol);
//                    return redirect('/usuarios');
//                } else {
//                    return view('/usuarios');
//                }

            if ($usuario->save()) {
//                $usuario = User::find($create->id);
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
	public function destroy($id) {
		try{

            $usuario = User::find($id);
            $roles = $usuario->roles()->get();

//            dd($id );foreach
            if (($roles->name) == 'admin'){
                return view ('usuarios.principal');
            }

			User::destroy($id);
			/*$affectedRows = User::where('id', '=', $id)->delete();*/
			$data['usuarios'] = User::all();
			
			foreach($data['usuarios'] as $usuario){
				$usuario['rol'] = $usuario->roles()->first();

			}

	        return view ('usuarios.usuarios', $data);
	    }
	    catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        } 
	        
	}

}
