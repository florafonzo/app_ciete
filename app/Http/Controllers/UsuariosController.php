<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use App\Models\Participante;
use App\Models\Profesor;
use Validator;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\RedirectResponse;

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
			$data['participantes'] = User::all();
            $data['profes'] = User::all();

			
			foreach($data['usuarios'] as $usuario){
				$usuario['rol'] = $usuario->roles()->first();

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
        try
        {
            $create = User::create([
                'email' => $request -> email,
                'password' => bcrypt($request -> password),
            ]);

            $usuario = User::find($create->id);

            if($request->id_rol == '3') {
                $create2 = Participante::findOrNew($request->id);
                $create2->id_usuario = $usuario->id;
                $create2->nombre = $request->nombre;
                $create2->apellido = $request->apellido;
                $create2->documento_identidad = $request->documento_identidad;
                $create2->foto = 'ruta';
                $create2->telefono = $request->telefono;
                $create2->celular = $request->telefono;
                $create2->correo_alternativo = 'correo@mail.com';
                $create2->twitter = 'twitter';
                $create2->ocupacion = 'estudiante';
                $create2->titulo_pregrado = 'licenciado';
                $create2->universidad = 'UCV';
            }

            if(($request->id_rol == '1') || ($request->id_rol == '2') || ($request->id_rol == '4')) {
//                dd();
                $create2 = Profesor::create([
                    'id_usuario' => $usuario->id,
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'documento_identidad' => $request->documento_identidad,
                    'telefono' => $request->telefono,
                    'foto' => 'ruta',
                    'celular' => '0416'


                ]);
            }

            if($create->save()) {
                $usuario = User::find($create->id);
                if ($create2->save()) {
                    $usuario->attachRole($request->id_rol);
                    return redirect('/usuarios');
                }else{
                    Session::set('error','Ha ocurrido un error inesperado');
                    DB::table('users')->where('id', '=', $usuario->id)->delete();
                    return view('usuarios.crear');
                }
            }else{
                Session::set('error','Ha ocurrido un error inesperado');
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
//            dd($id );
	        $data['usuarios'] = User::find($id);
	        $userRole = $data['usuarios']->roles()->first();
	        $data['rol'] = $userRole;
	        $data['roles'] = Role::all()->lists('display_name','id');

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
	public function update(UsuarioRequest $request, $id) {

		try {

			$usuario = User::find($id);

            $nombre = $request -> nombre;
            $apellido = $request -> apellido;
            $di = $request -> di;
            $telefono = $request -> telefono;
            $email = Input::get('email');
            
            if (!($email == $usuario->email)){
            	$email = $request -> email;
            }
            
            $password = bcrypt($request -> password);

            $usuario->nombre = $nombre;
            $usuario->apellido = $apellido;
            $usuario->documento_identidad = $di;
            $usuario->telefono = $telefono;
            $usuario->email = $email;
            $usuario->password = $password;

            $usuario->save();

            if($usuario->save()){
                $user = User::find($id);
                $user->roles()->detach();
                $user->attachRole($request -> rol);
                return redirect('/usuarios');
            }else{
                return view('/usuarios');
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
            $roles = $usuario->roles()->first();

//            dd($id );
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
