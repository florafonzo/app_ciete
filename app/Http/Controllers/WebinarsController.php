<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WebinarRequest;
use App\Http\Requests\WebinarEditarRequest;

use App\Models\Webinar;
use Illuminate\Http\Request;

class WebinarsController extends Controller {

	/**
	 * Muestra la vista de la lista de webinars si posee los permisos necesarios.
	 *
	 * @return Retorna la vista de la lista de webinars.
	 */
	public function index()
	{
		try{
			//Verificación de los permisos del usuario para poder realizar esta acción
			$permisos = [];
			$usuario_actual = Auth::user();
//			dd($usuario_actual);
			$roles = $usuario_actual->roles()->get();
			foreach($roles as $rol){
				$permisos = $rol->perms()->get();
			}
			$si_puede = false;
			foreach($permisos as $permiso){
				if(($permiso->name) == 'ver_webinars'){
					$si_puede = true;
				}
			}

			if($si_puede) {// Si el usuario posee los permisos necesarios continua con la acción
				$data['errores'] = '';
				$data['webinars'] = Webinar::orderBy('created_at')->get();   // Se obtienen todos los webinars

				return view('webinars.webinars', $data);  // Se muestra la lista de webinars

			}else{  // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

				return view('errors.sin_permiso');

			}

		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Muestra el formulario para crear un nuevo webinar si posee los permisos necesarios.
	 *
	 * @return Retorna la vista del formulario vacío.
	 */
	public function create()
	{
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
				if(($permiso->name) == 'crear_webinars'){
					$si_puede = true;
				}
			}

			if($si_puede) {

				// Se eliminan los datos guardados en sesion anteriormente
				Session::forget('nombre');
				Session::forget('cupos');
				Session::forget('fecha_inicio');
				Session::forget('fecha_fin');
				Session::forget('duracion');
				Session::forget('lugar');
				Session::forget('descripcion');
				Session::forget('link');

				$data['errores'] = '';

				return view('webinars.crear', $data);

			}else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

				return view('errors.sin_permiso');
			}
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

	/**
	 * Guarda el nuevo webinar con sus respectivos datos si el usuario posee los permisos necesarios.
	 *
	 * @param   WebinarRequest    $request (Se validan los campos ingresados por el usuario antes guardarlos mediante el Request)
	 *
	 * @return Retorna la vista de la lista de webinars con el nuevo webinar gregado.
	 */
	public function store(WebinarRequest $request)
	{
        try
        {
            //Verificación de los permisos del usuario para poder realizar esta acción
            $permisos = [];
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'crear_webinars'){
                    $si_puede = true;
                }
            }

            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';

                // Se guardan los datos ingresados por el usuario en sesion pra utilizarlos en caso de que se redirija
                // al usuari al formulario por algún error y no se pierdan los datos ingresados
                Session::set('nombre', $request->nombre);
                Session::set('cupos', $request->cupos);
                Session::set('fecha_inicio', $request->fecha_inicio);
                Session::set('fecha_fin', $request->fecha_fin);
                Session::set('duracion', $request->duracion);
                Session::set('lugar', $request->lugar);
                Session::set('descripcion', $request->descripcion);
                Session::set('link', $request->link);

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del Webinar
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    $data['errores'] = '';
                    $data['webinars'] = Webinar::all();   // Se obtienen todos los webinars
                    return view('webinars.crear', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        $data['errores'] = '';
                        $data['webinars'] = Webinar::all();   // Se obtienen todos los webinars
                        return view('webinars.crear', $data);
                    }
                }


                //  Se verifica si el usuario colocó una imagen en el formulario
//                if ($request->hasFile('imagen_carrusel')) {
//                    $imagen = $request->file('imagen_carrusel');
//                } else {
//                    $imagen = '';
//                }


//                Session::set('descripcion_carrusel', $request->descripcion_carrusel);

//                $activo_carrusel = false;
//                // Se verifica si el usuario elijió que el webinar este activo en el carrusel o no
//                if (Input::get('activo_carrusel') == "on") {
//                    $activo_carrusel = true;
//                } elseif (Input::get('activo_carrusel') == null) {
//                    $activo_carrusel = false;
//                }


                //Se verifica si el usuario seleccionó que el webinar esté activo en el carrusel
//                if (Input::get('activo_carrusel') == "on") {
//                    // Luego se verifica si los campos referente al carrusel estén completos
//                    if ((empty(Input::get('descripcion_carrusel'))) or !($request->hasFile('imagen_carrusel'))) {   // Si no están completos se
//                        // redirige al usuario indicandole el error
//                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
//
//                        return view('webinars.crear', $data);
//                    }
//                }


                // Se crea el nuevo webinar con los datos ingresados
                $create2 = Webinar::findOrNew($request->id);
                $create2->webinar_activo = "true";
                $create2->cupos = $request->cupos;
                $create2->nombre = $request->nombre;
                $create2->fecha_inicio = $request->fecha_inicio;
                $create2->fecha_fin = $request->fecha_fin;
                $create2->duracion = $request->duracion;
                $create2->lugar = $request->lugar;
                $create2->descripcion = $request->descripcion;
                $create2->link = $request->link;
//                $create2->imagen_carrusel = $imagen;
//                $create2->descripcion_carrusel = $request->descripcion_carrusel;
//                $create2->activo_carrusel = $activo_carrusel;

                // Se verifica que se haya creado el el webinar de forma correcta
                if ($create2->save()) {
                    return redirect('/webinars');

                } else {    // Si el webinar no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return view('webinars.crear');
                }
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e)
        {
            return view('errors.error')->with('error',$e->getMessage());
        }
	}



    /**
     * Se muestra el formulario de edicion de webinars si posee los permisos necesarios.
     *
     * @param  int  $id (id del webinar que se desea editar)
     *
     * @return Retorna vista del formulario para el editar el webinar deseado.
     */
	public function edit($id)
	{
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
                if(($permiso->name) == 'editar_webinars'){
                    $si_puede = true;
                }
            }
            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['webinars'] = Webinar::find($id); // Se obtiene la información del webinar seleccionado
//                dd( $data['webinars']);

                return view('webinars.editar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

    /**
     * Actualiza los datos del webinar seleccionado si posee los permisos necesarios
     *
     * @param  int  $id (id del webinar que se desea editar)
     * @param  WebinarRequest  $request (Se validan los campos ingresados por el usuario antes guardarlos mediante el Request)
     *
     * @return Retorna la lista de webinars con los datos actualizados.
     */
	public function update(WebinarEditarRequest $request, $id)
	{
        try{
//            dd($id);
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            $permisos = [];
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }

            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'editar_webinars'){
                    $si_puede = true;
                }
            }
            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $webinar = Webinar::find($id);

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del Webinar
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    return view('webinars.crear', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        return view('webinars.crear', $data);
                    }
                }

//                //Se verifica si el usuario seleccionó que el webinar esté activo en el carrusel
//                if (($request->activo_carrusel) == true) {
//                    // Luego se verifica si los campos referente al carrusel estén completos
//                    if ((empty(Input::get('descripcion_carrusel'))) or (empty(Input::get('imagen_carrusel')))) {// Si los campos no están completos se
//                        // redirige al usuario indicandole el error
//                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
//                        $data['webinars'] = Webinar::find($id);
//
//
//                        return view('webinars.crear', $data);
//                    }
//                }

//                //  Se verifica si el usuario colocó una imagen en el formulario
//                if ($request->hasFile('imagen_carrusel')) {
//                    $imagen = $request->file('imagen_carrusel');
//                } else {
//                    $imagen = $webinars->imagen_carrusel;
//                }

                // Se actualizan los datos del webinar seleccionado
                $webinar->cupos = $request->cupos;
                $webinar->nombre = $request->nombre;
                $webinar->fecha_inicio = $request->fecha_inicio;
                $webinar->fecha_fin = $request->fecha_fin;
                $webinar->duracion = $request->duracion;
                $webinar->lugar = $request->lugar;
                $webinar->descripcion = $request->descripcion;
                $webinar->link = $request->link;
//                $webinars->imagen_carrusel = $imagen;
//                $webinars->descripcion_carrusel = $request->descripcion_carrusel;
//                $webinars->activo_carrusel = 'false';

                // Se verifica que se haya creado el webinar de forma correcta
                if ($webinar->save()) {
                    return redirect('/webinars');
                } else {    // Si el webinar no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return view('webinars.editar');
                }

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

    /**
     * Desactiva el webinar deseado.
     *
     * @param  int  $id
     *
     * @return Retorna la vista de la lista de webinars actualizada.
     */
	public function destroy($id)
	{
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            $roles = $usuario_actual->roles()->get();
            $permisos = [];
            foreach($roles as $rol){
                $permisos = $rol->perms()->get();
            }
            $si_puede = false;
            foreach($permisos as $permiso){
                if(($permiso->name) == 'eliminar_webinars'){
                    $si_puede = true;
                }
            }

            if($si_puede) { // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del webinar que se desea eliminar
                $webinar = Webinar::find($id);
                //Se desactiva el webinar
                $webinar->webinar_activo = false;
                $webinar->save(); // se guarda

                // Se redirige al usuario a la lista de webinars actualizada
                $data['errores'] = '';
                $data['webinars'] = Webinar::all();

                return view('webinars.webinars', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

}
