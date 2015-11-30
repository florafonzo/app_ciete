<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
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
                'nombre' => $request -> nombre,
                'apellido' => $request -> apellido,
                'documento_identidad' => $request -> di,
                'telefono' => $request -> telefono,
                'email' => $request -> email,
                'password' => bcrypt($request -> password),
            ]);

            if($create->save()){
                $user = User::find($create->id);
                $user->attachRole($request -> rol);
                return redirect('/usuarios');
            }else{
                return view('/usuarios');
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
	public function edit($id) {
		try{
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
