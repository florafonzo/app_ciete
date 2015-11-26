<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use Validator;
use App\Http\Requests\UsuarioRequest;

use Illuminate\Http\Request;

class UsuariosController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data['usuarios'] = User::all();

		foreach($data['usuarios'] as $usuario){
			$usuario['rol'] = $usuario->roles()->first();

		}

		return view('usuarios.usuarios', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $data['roles'] = Role::all()->lists('display_name','id');
        return view ('usuarios.crear', $data);
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
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
