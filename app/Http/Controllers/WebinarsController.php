<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Models\ProfesorWebinar;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Maatwebsite\Excel\Facades\Excel as Excel;

use App\Models\Webinar;
use App\Models\ParticipanteWebinar;
use App\Models\Participante;
use App\Http\Requests\WebinarRequest;
use App\Http\Requests\WebinarEditarRequest;

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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción
				$data['errores'] = '';
                $data['busq_'] = false;
				$data['webinars'] = Webinar::where('webinar_activo', '=', true)
                                        ->orderBy('created_at')->get();   // Se obtienen todos los webinars
                foreach ($data['webinars'] as $web) {   //Formato fechas
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);
                }

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
                $data['webinars'] = array([]);
                $data['errores'] = '';
                $data['busq_'] = true;
                $param = Input::get('parametro');
                if($param == '0'){
                    $data['webinars'] = Webinar::orderBy('created_at')->get(); // Se obtienen todos los webinars con sus datos
                    foreach ($data['webinars'] as $web) {   //Formato fechas
                        $web['inicio'] = new DateTime($web->fecha_inicio);
                        $web['fin'] = new DateTime($web->fecha_fin);
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('webinars.webinars', $data);
                }
                if ($param == 'nombre'){
                    if (empty(Input::get('busqueda'))) {
                        $data['webinars'] = Webinar::orderBy('created_at')->get(); // Se obtienen todos los webinars con sus datos
                        foreach ($data['webinars'] as $web) {   //Formato fechas
                            $web['inicio'] = new DateTime($web->fecha_inicio);
                            $web['fin'] = new DateTime($web->fecha_fin);
                        }
                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('webinars.webinars', $data);
                    }else{
                        $busq = Input::get('busqueda');
                        $data['webinars'] = Webinar::where($param, 'ilike', '%'.$busq.'%')
                            ->where('webinar_activo', '=', 'true')
                            ->orderBy('created_at')->get();
                        foreach ($data['webinars'] as $web) {   //Formato fechas
                            $web['inicio'] = new DateTime($web->fecha_inicio);
                            $web['fin'] = new DateTime($web->fecha_fin);
                        }
                    }
                }

//                dd($data['webinars']);
                return view('webinars.webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
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
     * @return Retorna la vista de la lista de webinars desactivados deseados.
     */
    public function buscar2() {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_webinars')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['webinars'] = '';
                $data['errores'] = '';
                $data['busq_'] = true;
//                $data['busq'] = true;
                $param = Input::get('parametro');
                if($param == '0'){
//                    $data['busq'] = false;
                    $data['webinars'] = Webinar::where('webinar_activo', '=', false)
                                            ->orderBy('created_at')->get(); // Se obtienen todos los webinars desactivados con sus datos
                    foreach ($data['webinars'] as $web) {   //Formato fechas
                        $web['inicio'] = new DateTime($web->fecha_inicio);
                        $web['fin'] = new DateTime($web->fecha_fin);
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('webinars.desactivados', $data);
                }
                if ($param == 'nombre'){
                    if (empty(Input::get('busqueda'))) {
//                        $data['busq'] = false;
                        $data['webinars'] = Webinar::where('webinar_activo', '=', false)
                                            ->orderBy('created_at')->get(); // Se obtienen todos los webinars desactivados con sus datos
                        foreach ($data['webinars'] as $web) {   //Formato fechas
                            $web['inicio'] = new DateTime($web->fecha_inicio);
                            $web['fin'] = new DateTime($web->fecha_fin);
                        }
                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('webinars.desactivados', $data);
                    }else{
                        $busq = Input::get('busqueda');
                        $data['webinars'] = Webinar::where($param, 'ilike', '%'.$busq.'%')
                            ->where('webinar_activo', '=', 'false')
                            ->orderBy('created_at')->get();
                        foreach ($data['webinars'] as $web) {   //Formato fechas
                            $web['inicio'] = new DateTime($web->fecha_inicio);
                            $web['fin'] = new DateTime($web->fecha_fin);
                        }
                    }
                }

                return view('webinars.desactivados', $data);


            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['activo_'] = false;
				// Se eliminan los datos guardados en sesion anteriormente
				Session::forget('nombre');
				Session::forget('secciones');
                Session::forget('min');
                Session::forget('max');
				Session::forget('fecha_inicio');
				Session::forget('fecha_fin');
				Session::forget('especificaciones');
				Session::forget('descripcion_carrusel');

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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $img_nueva = Input::get('cortar');
                $img_cargada = Input::get('img_');

                if($img_nueva == 'yes'){
                    $data['activo_'] =  true;
                    $data['ruta'] = Input::get('dir');
                    Session::flash('cortar', 'yes');
//                    Session::flash('img_carg', 'yes');
                }else{
                    $data['activo_'] =  false;
                }

                // Se guardan los datos ingresados por el usuario en sesion pra utilizarlos en caso de que se redirija
                // al usuari al formulario por algún error y no se pierdan los datos ingresados
                Session::set('nombre', $request->nombre);
                Session::set('secciones', $request->secciones);
                Session::set('min', $request->mini);
                Session::set('max', $request->maxi);
                Session::set('fecha_inicio', $request->fecha_inicio);
                Session::set('fecha_fin', $request->fecha_fin);
                Session::set('especificaciones', $request->especificaciones);
                Session::set('descripcion_carrusel', $request->descripcion_carrusel);
//                Session::set('duracion', $request->duracion);
//                Session::set('lugar', $request->lugar);
//                Session::set('descripcion', $request->descripcion);
//                Session::set('link', $request->link);

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del Webinar
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    $data['errores'] = '';
                    //$data['webinars'] = Webinar::all();   // Se obtienen todos los webinars
                    return view('webinars.crear', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        $data['errores'] = '';
                        //$data['webinars'] = Webinar::all();   // Se obtienen todos los webinars
                        return view('webinars.crear', $data);
                    }
                }

                //se verifica que el MIN por seccion sea igual o menor al MAX
                if (($request->mini) > ($request->maxi)) {
                    //Session::set('error', 'La cantidad minima de cupos por seccion debe ser igual o menor a la canidad maxima');
                    $data['errores'] = 'La cantidad minima de cupos por seccion debe ser igual o menor a la canidad maxima';

                    return view('webinars.crear', $data);
                }

//                Session::set('descripcion_carrusel', $request->descripcion_carrusel);

                $activo_carrusel = false;

                //Se verifica si el usuario seleccionó que el webinar esté activo en el carrusel
                //if (Input::get('activo_carrusel') == "on") {
                if ($request->activo_carrusel == "on") {    
                    $activo_carrusel = true;
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ((empty(Input::get('descripcion_carrusel'))) or ($img_cargada != 'yes')) {   // Si no están completos se
                        // redirige al usuario indicandole el error
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";

                        return view('webinars.crear', $data);
                    }
                }


                // Se crea el nuevo webinar con los datos ingresados
                $create2 = Webinar::findOrNew($request->id);
                $create2->webinar_activo = "true";
                $create2->secciones = $request->secciones;
                $create2->min = $request->mini;
                $create2->max = $request->maxi;
                $create2->nombre = $request->nombre;
                $create2->fecha_inicio = $request->fecha_inicio;
                $create2->fecha_fin = $request->fecha_fin;
                $create2->especificaciones = $request->especificaciones;
                $create2->descripcion_carrusel = $request->descripcion_carrusel;
                $create2->activo_carrusel = $activo_carrusel;

                if($img_nueva == 'yes'){
                    $file = Input::get('dir');
                    $file = str_replace('data:image/png;base64,', '', $file);
                    $nombreTemporal = 'imagen_webinar_' . date('dmY') . '_' . date('His') . ".jpg";
                    $create2->imagen_carrusel = $nombreTemporal;

                    $imagen = Image::make($file);
                    $payload = (string)$imagen->encode();
                    Storage::put(
                        '/images/images_carrusel/webinars/'. $nombreTemporal,
                        $payload
                    );
                }else{
                    $create2->imagen_carrusel = '';
                }

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
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['webinars'] = Webinar::find($id); // Se obtiene la información del webinar seleccionado
                $data['activo_'] =  $data['webinars']->activo_carrusel;

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
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['activo_'] =  true;
                $webinar = Webinar::find($id);
                $img_nueva = Input::get('cortar');
                $img_cargada = Input::get('img_');
                $data['webinars'] = Webinar::find($id);

                if($img_nueva == 'yes'){
                    $data['activo_'] =  true;
                    $data['ruta'] = Input::get('dir');
                    Session::flash('cortar', 'yes');
                    Session::flash('img_carg', 'yes');
                }else{
                    $data['activo_'] =  $webinar->activo_carrusel;
                }

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del Webinar
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    return view('webinars.editar', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        return view('webinars.editar', $data);
                    }
                }

                //se verifica que el MIN por seccion sea igual o menor al MAX
                if (($request->mini) > ($request->maxi)) {
                    Session::set('error', 'La cantidad minima de cupos por seccion debe ser igual o menor a la canidad maxima');
                    $data['errores'] = '';
                    return view('webinars.editar', $data);
                }

                $activo_carrusel = false;
                //Se verifica si el usuario seleccionó que el webinar esté activo en el carrusel
                if (($request->activo_carrusel) == "on") {
                    $activo_carrusel = true;
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ((empty(Input::get('descripcion_carrusel'))) or ($img_cargada == null)) {// Si los campos no están completos se
                        // redirige al usuario indicandole el error
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        return view('webinars.editar', $data);
                    }
                }

//                //  Se verifica si el usuario colocó una imagen en el formulario
//                if ($request->hasFile('imagen_carrusel')) {
//                    $imagen = $request->file('imagen_carrusel');
//                } else {
//                    $imagen = $webinars->imagen_carrusel;
//                }

                // Se actualizan los datos del webinar seleccionado
                $webinar->secciones = $request->secciones;
                $webinar->min = $request->mini;
                $webinar->max = $request->maxi;
                $webinar->nombre = $request->nombre;
                $webinar->fecha_inicio = $request->fecha_inicio;
                $webinar->fecha_fin = $request->fecha_fin;
                $webinar->especificaciones = $request->especificaciones;
                $webinar->imagen_carrusel = '';
                $webinar->descripcion_carrusel = $request->descripcion_carrusel;
                $webinar->activo_carrusel = $activo_carrusel;

                if($img_nueva == 'yes'){
                    $file = Input::get('dir');
                    if($webinar->imagen_carrusel != null){
                        Storage::delete('/images/images_carrusel/webinars/' . $request->file_viejo);
                    }
                    $file = str_replace('data:image/png;base64,', '', $file);
                    $nombreTemporal = 'imagen_webinar_' . date('dmY') . '_' . date('His') . ".jpg";
                    $webinar->imagen_carrusel = $nombreTemporal;

                    $imagen = Image::make($file);
                    $payload = (string)$imagen->encode();
                    Storage::put(
                        '/images/images_carrusel/webinars/'. $nombreTemporal,
                        $payload
                    );
                }else{
                    $webinar->imagen_carrusel = '';
                }

                // Se verifica que se haya creado el webinar de forma correcta
                if ($webinar->save()) {
                    return redirect('/webinars');
                } else {    // Si el webinar no se ha creado bien se redirige al formulario de creación y se le indica al usuario el error
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    $data['webinars'] = Webinar::find($id);

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
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('eliminar_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del webinar que se desea eliminar
                $webinar = Webinar::find($id);
                $data['busq_'] = false;
                //Se desactiva el webinar
                $webinar->webinar_activo = false;
                $webinar->save(); // se guarda

                // Se redirige al usuario a la lista de webinars actualizada
                $data['errores'] = '';
                $data['webinars'] = Webinar::orderBy('created_at')->get();;
                foreach ($data['webinars'] as $web) {   //Formato fechas
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);
                }

                return view('webinars.webinars', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

    public function indexDesactivados()
    {
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_webinars')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
//                $data['busq'] = false;
                $data['webinars'] = Webinar::where('webinar_activo', '=', false)
                                            ->orderBy('created_at')->get(); // Se obtienen todos los webinars descativados con sus datos
                foreach ($data['webinars'] as $web) {   //Formato fechas
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);
                }

                return view('webinars.desactivados', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function activar($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_cursos')) {  // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del webinars que se desea activar
                $webinar = Webinar::find($id);
                $data['busq_'] = false;
//                $data['busq'] = false;

                //Se activa el webinar
                $webinar->webinar_activo = true;
                $webinar->save(); // se guarda

                // Se redirige al usuario a la lista de webinars actualizada
                $data['errores'] = '';
                $data['webinars'] = Webinar::orderBy('created_at')->get();
                foreach ($data['webinars'] as $web) {   //Formato fechas
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);
                }

                return view('webinars.desactivados', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function cambiarImagen()
    {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['activo_'] = true;
                Session::flash('imagen', 'yes');
                Session::flash('img_carg', 'yes');

                return view('webinars.crear', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }
    }

    public function procesarImagen() {

        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['ruta'] = Input::get('rutas');
                $data['errores'] = '';
                $data['activo_'] = true;
                Session::flash('imagen', null);
                Session::flash('cortar', 'yes');
                Session::flash('img_carg', 'yes');

                return view('webinars.crear', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }

    }

    public function cambiarImagen1($id)
    {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['activo_'] = true;
                Session::flash('imagen', 'yes');
                Session::flash('img_carg', 'yes');

                $data['errores'] = '';
                $data['webinars'] = Webinar::find($id); // Se obtiene la información del webinar seleccionado
                $data['inicio'] = new DateTime($data['webinars']->fecha_inicio);
                $data['fin'] = new DateTime($data['webinars']->fecha_fin);


                return view('webinars.editar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }
    }

    public function procesarImagen1($id) {

        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('crear_webinars')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['ruta'] = Input::get('rutas');
                $data['activo_'] = true;
                Session::flash('imagen', null);
                Session::flash('cortar', 'yes');
                Session::flash('img_carg', 'yes');
//                dd(Session::get('cortar'));

                $data['errores'] = '';
                $data['webinars'] = Webinar::find($id); // Se obtiene la información del webinars seleccionado
                $data['inicio'] = new DateTime($data['webinars']->fecha_inicio);
                $data['fin'] = new DateTime($data['webinars']->fecha_fin);

                return view('webinars.editar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }

    }


//    Funcion para ordenar por apellido arreglos de objetos
    public function cmp($a, $b) {
        return strcmp($a->apellido, $b->apellido);
    }


//    ------------------------ Participantes ------------------------------------

    public function WebinarSeccionesParts($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('participantes_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id);
                $arr = [];
                $secciones = ParticipanteWebinar::where('id_webinar', '=', $id)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('webinars.participantes.secciones', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function webinarParticipantes($id, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('participantes_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['participantes'] = [];
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $data['webinar'] = Webinar::find($id);
                $web_part = ParticipanteWebinar::where('id_webinar', '=', $id)->where('seccion', '=', $seccion)->get();
                if($web_part->count()){
                    foreach ($web_part as $index => $web) {
                        $data['participantes'][$index] = Participante::where('id', '=', $web->id_participante)->orderBy('apellido')->get();
                    }
                }

                return view('webinars.participantes.participantes', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

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
     * @return Retorna la vista de la lista de participantes deseados.
     */
    public function buscarParticipante($id_webinar, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id_webinar);
                $data['seccion'] = $seccion;
                $data['participantes'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                $data['busq'] = true;
                if($param == '0'){
                    $data['busq'] = false;
                    $participantes = ParticipanteWebinar::where('id_webinar', '=', $id_webinar)->where('seccion', '=', $seccion)->select('id_participante')->get();
                    if($participantes != null) {
                        foreach ($participantes as $index => $part) {
                            $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('webinars.participantes.participantes', $data);
                }
                if (empty(Input::get('busqueda'))) {
                    $data['busq'] = false;
                    $participantes = ParticipanteWebinar::where('id_webinar', '=', $id_webinar)->where('seccion', '=', $seccion)->select('id_participante')->get();
                    if($participantes != null) {
                        foreach ($participantes as $index => $part) {
                            $data['participantes'][$index] = Participante::where('id', '=', $part->id_participante)->get();
                        }
                    }
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return view('webinars.participantes.participantes', $data);
                }else{
                    $busq = Input::get('busqueda');
                }

                $participantes = Participante::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if($participantes != null) {
                    foreach ($participantes as $index => $part) {
                        $existe = ParticipanteWebinar::where('id_webinar', '=', $id_webinar)
                            ->where('seccion', '=', $seccion)
                            ->where('id_participante', '=', $part->id)->get();
                        if($existe->count()) {
                            $data['participantes'][$index] = $part;
                        }
                    }
                }

                return view('webinars.participantes.participantes', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function webinarParticipantesAgregar($id, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_part_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $data['webinar'] = Webinar::find($id);
                $arr = [];
                $todos = DB::table('participante_webinars')->select('id_participante')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_participante;
                }
                $no_estan = DB::table('participantes')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ParticipanteWebinar::all();
                if($existe->count()) {
                    $noParticipantes = ParticipanteWebinar::where('id_webinar', '=', $id)->orderBy('id_participante')->select('id_participante')->get();

                    if ($noParticipantes->count()) {
                        foreach ($noParticipantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }

                        $participantes = ParticipanteWebinar::where('id_webinar', '!=', $id)
                            ->whereNotIn('id_participante', $arr)
                            ->select('id_participante')
                            ->orderBy('id_participante')
                            ->get();
                        $arr = [];
                        foreach ($participantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }
                        $parts = array_unique($arr);

                        if($parts != null) {
                            foreach ($parts as $index => $id_part) {
                                $data['participantes'][$index] = Participante::find($id_part);
                            }
                        }else{
                            $data['participantes'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['participantes']);
                            foreach ($no_estan as $datos) {
                                $data['participantes'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['participantes'] != '') {
                            usort($data['participantes'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['participantes'] = Participante::orderBy('apellido')->get();
                    }
                }else{
                    $data['participantes'] = Participante::orderBy('apellido')->get();
                }

                return view('webinars.participantes.agregar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

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
     * @return Retorna la vista de la lista de participantes deseados.
     */
    public function buscarParticipanteAgregar($id_webinar, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id_webinar);
                $data['seccion'] = $seccion;
                $data['participantes'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                if($param == '0'){
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return $this->webinarParticipantesAgregar($id_webinar, $seccion);
                }
                if (empty(Input::get('busqueda'))) {
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return $this->webinarParticipantesAgregar($id_webinar, $seccion);
                }else{
                    $busq = Input::get('busqueda');
                }

                $arr = [];
                $todos = DB::table('participante_webinars')->select('id_participante')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_participante;
                }
                $no_estan = DB::table('participantes')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ParticipanteWebinar::all();
                if($existe->count()) {
                    $noParticipantes = ParticipanteWebinar::where('id_webinar', '=', $id_webinar)->orderBy('id_participante')->select('id_participante')->get();

                    if ($noParticipantes->count()) {
                        foreach ($noParticipantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }

                        $participantes = ParticipanteWebinar::where('id_webinar', '!=', $id_webinar)
                            ->whereNotIn('id_participante', $arr)
                            ->select('id_participante')
                            ->orderBy('id_participante')
                            ->get();
                        $arr = [];
                        foreach ($participantes as $index => $todo) {
                            $arr[$index] = $todo->id_participante;
                        }
                        $parts = array_unique($arr);

                        if($parts != null) {
                            foreach ($parts as $index => $id_part) {
                                $data['parts'][$index] = Participante::find($id_part);
                            }
                        }else{
                            $data['parts'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['parts']);
                            foreach ($no_estan as $datos) {
                                $data['parts'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['parts'] != '') {
                            usort($data['parts'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['parts'] = Participante::orderBy('apellido')->get();
                    }
                }else{
                    $data['parts'] = Participante::orderBy('apellido')->get();
                }
//                $participantes = Participante::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if($data['parts'] != null) {
                    foreach ($data['parts'] as $index => $part) {
                        $existe = Participante::where('id', '=', $part->id)
                            ->where($param, 'ilike', '%'.$busq.'%')->get();
                        if($existe->count()) {
                            $data['participantes'][$index] = $part;
                        }
                    }
                }

                return view('webinars.participantes.agregar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function webinarParticipantesGuardar($id_webinar, $seccion, $id_part) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_part_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $webinar = Webinar::find($id_webinar);
                $participante = Participante::find($id_part);
                $existe = ParticipanteWebinar::where('id_participante', '=', $id_part)->where('id_webinar', '=', $id_webinar)->get();

                if($existe->count()) {
                    Session::set('error', 'Ya existe el registro en la base de datos');
                    return $this->webinarParticipantesAgregar($id_webinar, $seccion);
                }else{
                    if ($webinar != null && $participante != null) {
                        $part_web = new ParticipanteWebinar;
                        $part_web->id_participante = $id_part;
                        $part_web->id_webinar = $id_webinar;
                        $part_web->seccion = 'B';

//                        $part_web = ParticipanteWebinar::create([
//                            'id_participante' => $id_part,
//                            'id_webinar' => $id_webinar,
//                            'seccion' => 'B',
//                        ]);
                        $part_web->save();

                        if ($part_web->save()) {
                            Session::set('mensaje', 'Participante agregado con éxito');
                            return $this->webinarParticipantesAgregar($id_webinar, $seccion);
                        } else {
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return $this->webinarParticipantesAgregar($id_webinar, $seccion);
                        }
                    } else {
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        return $this->index();
                    }
                }


            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function webinarParticipantesEliminar($id_webinar, $id_part) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('eliminar_part_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';

                $part_web = ParticipanteWebinar::where('id_webinar', '=', $id_webinar)->where('id_participante', '=', $id_part)->first();

                DB::table('participante_webinars')->where('id', '=', $part_web->id)->delete();

                $data['participantes'] = [];
                $data['webinar'] = Webinar::find($id_webinar);
                $web_part = ParticipanteWebinar::where('id_webinar', '=', $id_webinar)->get();
                if($web_part->count()){
                    foreach ($web_part as $index => $web) {
                        $data['participantes'][$index] = Participante::where('id', '=', $web->id_participante)->orderBy('apellido')->get();
                    }
                }

                Session::set('mensaje', 'Usuario eliminado con éxito');
                return view('webinars.participantes.participantes', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
//    -------------------------------------------------------------------------------------------

//--------------------------------------- Profesores --------------------------------------------

    public function WebinarSeccionesProfes($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('participantes_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id);
                $arr = [];
                $secciones = ProfesorWebinar::where('id_webinar', '=', $id)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('webinars.profesores.secciones', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function webinarProfesores($id, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('profesores_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['profesores'] = [];
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $data['webinar'] = Webinar::find($id);
                $web_prof = ProfesorWebinar::where('id_webinar', '=', $id)->where('seccion', '=', $seccion)->get();
                if($web_prof->count()){
                    foreach ($web_prof as $index => $web) {
                        $data['profesores'][$index] = Profesor::where('id', '=', $web->id_profesor)->orderBy('apellido')->get();
                    }
                }

                return view('webinars.profesores.profesores', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

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
     * @return Retorna la vista de la lista de profesores deseados.
     */
    public function buscarProfesor($id_webinar, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id_webinar);
                $data['seccion'] = $seccion;
                $data['profesores'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                $data['busq'] = true;
                if($param == '0'){
                    $data['busq'] = false;
                    $profesores = ProfesorWebinar::where('id_webinar', '=', $id_webinar)->where('seccion', '=', $seccion)->select('id_profesor')->get();
                    if($profesores != null) {
                        foreach ($profesores as $index => $prof) {
                            $data['profesores'][$index] = Profesor::where('id', '=', $prof->id_profesor)->get();
                        }
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('webinars.profesores.profesores', $data);
                }
                if (empty(Input::get('busqueda'))) {
                    $data['busq'] = false;
                    $profesores = ProfesorWebinar::where('id_webinar', '=', $id_webinar)->where('seccion', '=', $seccion)->select('id_profesor')->get();
                    if($profesores != null) {
                        foreach ($profesores as $index => $prof) {
                            $data['profesores'][$index] = Profesor::where('id', '=', $prof->id_profesor)->get();
                        }
                    }
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return view('webinars.profesores.profesores', $data);
                }else{
                    $busq = Input::get('busqueda');
                }

                $profesores = Profesor::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if($profesores != null) {
                    foreach ($profesores as $index => $prof) {
                        $existe = ProfesorWebinar::where('id_webinar', '=', $id_webinar)
                            ->where('seccion', '=', $seccion)
                            ->where('id_profesor', '=', $prof->id)->get();
                        if($existe->count()) {
                            $data['profesores'][$index] = $prof;
                        }
                    }
                }

                return view('webinars.profesores.profesores', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function webinarProfesoresAgregar($id, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_prof_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id);
                $data['seccion'] = $seccion;
                $data['profesores'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $arr = [];
                $todos = DB::table('profesor_webinars')->select('id_profesor')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_profesor;
                }
                $no_estan = DB::table('profesores')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ProfesorWebinar::all();
                if($existe->count()) {
                    $noProfesor = ProfesorWebinar::where('id_webinar', '=', $id)->orderBy('id_profesor')->select('id_profesor')->get();

                    if ($noProfesor->count()) {
                        foreach ($noProfesor as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }

                        $profesores = ProfesorWebinar::where('id_webinar', '!=', $id)
                            ->whereNotIn('id_profesor', $arr)
                            ->select('id_profesor')
                            ->orderBy('id_profesor')
                            ->get();
                        $arr = [];
                        foreach ($profesores as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }
                        $profes = array_unique($arr);

                        if($profes != null) {
                            foreach ($profes as $index => $id_prof) {
                                $data['profesores'][$index] = Profesor::find($id_prof);
                            }
                        }else{
                            $data['profesores'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['profesores']);
                            foreach ($no_estan as $datos) {
                                $data['profesores'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['profesores'] != '') {
                            usort($data['profesores'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['profesores'] = Profesor::orderBy('apellido')->get();
                    }
                }else{
                    $data['profesores'] = Profesor::orderBy('apellido')->get();
                }

                return view('webinars.profesores.agregar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

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
     * @return Retorna la vista de la lista de profesores deseados.
     */
    public function buscarProfesorAgregar($id_webinar, $seccion) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_usuarios')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id_webinar);
                $data['seccion'] = $seccion;
                $data['profesores'] = '';
                $seccion = str_replace(' ', '', $seccion);
                $param = Input::get('parametro');
                $data['busq_'] = true;
                $data['busq'] = true;
                if($param == '0'){
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return $this->webinarProfesoresAgregar($id_webinar, $seccion);
                }
                if (empty(Input::get('busqueda'))) {
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return $this->webinarProfesoresAgregar($id_webinar, $seccion);
                }else{
                    $busq = Input::get('busqueda');
                }

                $arr = [];
                $todos = DB::table('profesor_webinars')->select('id_profesor')->get();
                foreach ($todos as $index => $todo) {
                    $arr[$index] = $todo->id_profesor;
                }
                $no_estan = DB::table('profesores')->whereNotIn('id',$arr)->get();
                $arr = [];

                $existe =  ProfesorWebinar::all();
                if($existe->count()) {
                    $noProfesor = ProfesorWebinar::where('id_webinar', '=', $id_webinar)->orderBy('id_profesor')->select('id_profesor')->get();

                    if ($noProfesor->count()) {
                        foreach ($noProfesor as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }

                        $profesores = ProfesorWebinar::where('id_webinar', '!=', $id_webinar)
                            ->whereNotIn('id_profesor', $arr)
                            ->select('id_profesor')
                            ->orderBy('id_profesor')
                            ->get();
                        $arr = [];
                        foreach ($profesores as $index => $todo) {
                            $arr[$index] = $todo->id_profesor;
                        }
                        $profes = array_unique($arr);

                        if($profes != null) {
                            foreach ($profes as $index => $id_prof) {
                                $data['profes'][$index] = Profesor::find($id_prof);
                            }
                        }else{
                            $data['profes'] = '';
                        }
                        if ($no_estan != null) {
                            $tam = count($data['profes']);
                            foreach ($no_estan as $datos) {
                                $data['profes'][$tam] = $datos;
                                $tam++;
                            }
                        }

                        if($data['profes'] != '') {
                            usort($data['profes'], array($this, "cmp")); //Ordenar por orden alfabetico segun el apellido
                        }

                    }else{
                        $data['profes'] = Profesor::orderBy('apellido')->get();
                    }
                }else{
                    $data['profes'] = Profesor::orderBy('apellido')->get();
                }
//                $profesores = Profesor::where($param, 'ilike', '%'.$busq.'%')->orderBy($param)->get();
                if( $data['profes'] != null) {
                    foreach ( $data['profes'] as $index => $prof) {
                        $existe = Profesor::where('id', '=', $prof->id)->where($param, 'ilike', '%'.$busq.'%')->get();
                        if($existe->count()) {
                            $data['profesores'][$index] = $prof;
                        }
                    }
                }

                return view('webinars.profesores.agregar', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function webinarProfesoresGuardar($id_web,$seccion, $id_profesor) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('agregar_prof_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['seccion'] = $seccion;
                $seccion = str_replace(' ', '', $seccion);
                $webinar = Webinar::find($id_web);
                $profesor = Profesor::find($id_profesor);
                $existe = ProfesorWebinar::where('id_profesor', '=', $id_profesor)->where('id_webinar', '=', $id_web)->get();

                if($existe->count()) {
                    Session::set('error', 'Ya existe el registro en la base de datos');
                    return $this->webinarProfesoresAgregar($id_web, $seccion);
                }else{
                    if ($webinar != null || $profesor != null) {

                        $prof_web = new ProfesorWebinar;
                        $prof_web->id_profesor = $id_profesor;
                        $prof_web->id_webinar = $id_web;
//                        $prof_web = ProfesorWebinar::create([
//                            'id_profesor' => $id_profesor,
//                            'id_webinar' => $id_web
//                        ]);
                        $prof_web->save();

                        if ($prof_web->save()) {
                            Session::set('mensaje', 'Profesor agregado con éxito');
                            return $this->webinarProfesoresAgregar($id_web, $seccion);
                        } else {
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return $this->webinarProfesoresAgregar($id_web, $seccion);
                        }
                    } else {
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        return $this->index();
                    }
                }


            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function webinarProfesoresEliminar($id_web, $id_profesor) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('eliminar_prof_webinar')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $prof_web = ProfesorWebinar::where('id_webinar', '=', $id_web)->where('id_profesor', '=', $id_profesor)->first();

                DB::table('profesor_webinars')->where('id', '=', $prof_web->id)->delete();

                $data['profesores'] = [];
                $data['webinar'] = Webinar::find($id_web);
                $webinar_prof = ProfesorWebinar::where('id_webinar', '=', $id_web)->get();
                if($webinar_prof->count()){
                    foreach ($webinar_prof as $index => $web) {
                        $data['profesores'][$index] = Profesor::where('id', '=', $web->id_profesor)->orderBy('apellido')->get();
                    }
                }

                Session::set('mensaje', 'Usuario eliminado con éxito');
                return view('webinars.profesores.profesores', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
//-----------------------------------------------------------------------------------------------

//-------------------------------Moodle----------------------------------------------------------

    public function seccionesMoodle($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('participantes_curso')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinar'] = Webinar::find($id);
                $arr = [];
                $secciones = ParticipanteWebinar::where('id_webinar', '=', $id)->select('seccion')->get();
                foreach ($secciones as $index => $seccion) {
                    $arr[$index] = $seccion->seccion;
                }
                sort($arr);
                $data['secciones'] = array_unique($arr);

                return view('webinars.moodle.secciones', $data);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }


    public function listaMoodle($id, $seccion) {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('listar_alumnos')) {// Si el usuario posee los permisos necesarios continua con la acción
                $webinar = Webinar::find($id);
                $seccion = str_replace(' ', '', $seccion);
                $web_part = ParticipanteWebinar::where('id_webinar', '=', $id)->where('seccion', '=', $seccion)->get();
                $participante = [];
                if($web_part->count()){
                    foreach ($web_part as $index => $web) {
                        $part = Participante::where('id', '=', $web->id_participante)->get();
                        $usuario = User::where('id', '=', $part[0]->id_usuario)->get();
                        $username = explode("@", $usuario[0]->email);
                        $participante[$index][0] = $username[0];
                        $participante[$index][1] = $part[0]->documento_identidad;
                        $participante[$index][2] = $usuario[0]->nombre;
                        $participante[$index][3] = $usuario[0]->apellido;
                        $participante[$index][4] = $usuario[0]->email;
                        $participante[$index][5] = $webinar->nombre;
                    }
                }

//                dd($participantes);
//                $data = $participantes;

                Excel::create('Webinar_'.$webinar->nombre.'_seccion_'.$seccion, function($excel) use($participante){
                    $excel->sheet('Sheetname', function($sheet) use($participante) {
                        $datos = ['username','password','firstname','lastname','email','course'];
                        $data = array(
                            array('data1', 'data2'),
                            array('data3', 'data4')
                        );
                        $data[0] = $datos;
                        foreach ($participante as $index => $part) {
                            $data[$index+1] = $part;
                        }

                        $sheet->fromArray($data, null, 'A1' , false, false);

                    });

                })->download('csv');

//                return ;

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch(Exception $e){
            return view('errors.error')->with('error',$e->getMessage());
        }
    }
//-----------------------------------------------------------------------------------------------



}
