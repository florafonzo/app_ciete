<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Models\ProfesorWebinar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WebinarRequest;
use App\Http\Requests\WebinarEditarRequest;
use DateTime;

use App\Models\Webinar;
use App\Models\ParticipanteWebinar;
use App\Models\Participante;

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
				$data['webinars'] = Webinar::orderBy('created_at')->get();   // Se obtienen todos los webinars
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

				// Se eliminan los datos guardados en sesion anteriormente
				Session::forget('nombre');
				Session::forget('secciones');
                Session::forget('min');
                Session::forget('max');
				Session::forget('fecha_inicio');
				Session::forget('fecha_fin');
				Session::forget('especificaciones');
				Session::forget('descripcion_carrusel');
//				Session::forget('duracion');
//				Session::forget('lugar');
//				Session::forget('descripcion');
//				Session::forget('link');

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


                //  Se verifica si el usuario colocó una imagen en el formulario
//                if ($request->hasFile('imagen_carrusel')) {
//                    $imagen = $request->file('imagen_carrusel');
//                } else {
//                    $imagen = '';
//                }


//                Session::set('descripcion_carrusel', $request->descripcion_carrusel);

                $activo_carrusel = false;

                //Se verifica si el usuario seleccionó que el webinar esté activo en el carrusel
                //if (Input::get('activo_carrusel') == "on") {
                if ($request->activo_carrusel == "on") {    
                    $activo_carrusel = true;
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ((empty(Input::get('descripcion_carrusel'))) or !($request->hasFile('imagen_carrusel'))) {   // Si no están completos se
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
//                $create2->duracion = $request->duracion;
//                $create2->lugar = $request->lugar;
//                $create2->descripcion = $request->descripcion;
//                $create2->link = $request->link;
                $create2->imagen_carrusel = '';
                $create2->descripcion_carrusel = $request->descripcion_carrusel;
                $create2->activo_carrusel = $activo_carrusel;

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
                $webinar = Webinar::find($id);

                $fecha_actual = date('Y-m-d');// Se obtiene la fecha actual para validar las fechas de inicio y fin del Webinar
                if(($request->fecha_inicio) <= $fecha_actual) {
                    Session::set('error', 'La fecha de inicio debe ser mayor a la fecha actual');
                    $data['webinars'] = Webinar::find($id);

                    return view('webinars.editar', $data);

                }else{
                    if (($request->fecha_inicio) > ($request->fecha_fin)) {
                        Session::set('error', 'La fecha de inicio debe ser igual o menor a la fecha fin');
                        $data['webinars'] = Webinar::find($id);

                        return view('webinars.editar', $data);
                    }
                }

                //se verifica que el MIN por seccion sea igual o menor al MAX
                if (($request->mini) > ($request->maxi)) {
                    Session::set('error', 'La cantidad minima de cupos por seccion debe ser igual o menor a la canidad maxima');
                    $data['errores'] = '';
                    $data['webinars'] = Webinar::find($id);

                    return view('webinars.editar', $data);
                }

                $activo_carrusel = false;
                //Se verifica si el usuario seleccionó que el webinar esté activo en el carrusel
                if (($request->activo_carrusel) == "on") {
                    $activo_carrusel = true;
                    // Luego se verifica si los campos referente al carrusel estén completos
                    if ((empty(Input::get('descripcion_carrusel'))) or (empty(Input::get('imagen_carrusel')))) {// Si los campos no están completos se
                        // redirige al usuario indicandole el error
                        $data['errores'] = $data['errores'] . "  Debe completar los campos de descripcion y imagen del Carrusel";
                        $data['webinars'] = Webinar::find($id);

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
//                $webinar->duracion = $request->duracion;
//                $webinar->lugar = $request->lugar;
//                $webinar->descripcion = $request->descripcion;
//                $webinar->link = $request->link;
                $webinar->imagen_carrusel = '';
                $webinar->descripcion_carrusel = $request->descripcion_carrusel;
                $webinar->activo_carrusel = $activo_carrusel;

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
                $data['webinars'] = Webinar::orderBy('created_at')->get(); // Se obtienen todos los webinars con sus datos
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

//    Funcion para ordenar por apellido arreglos de objetos
    public function cmp($a, $b) {
        return strcmp($a->apellido, $b->apellido);
    }


//    ------------------------ Participantes ------------------------------------
    public function webinarParticipantes($id) {
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
                $data['participantes'] = [];
                $data['webinar'] = Webinar::find($id);
                $web_part = ParticipanteWebinar::where('id_webinar', '=', $id)->get();
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

    public function webinarParticipantesAgregar($id) {
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

    public function webinarParticipantesGuardar($id_webinar, $id_part) {
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
                $webinar = Webinar::find($id_webinar);
                $participante = Participante::find($id_part);
                $existe = ParticipanteWebinar::where('id_participante', '=', $id_part)->where('id_webinar', '=', $id_webinar)->get();

                if($existe->count()) {
                    Session::set('error', 'Ya existe el registro en la base de datos');
                    return $this->webinarParticipantesAgregar($id_webinar);
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
                            return $this->webinarParticipantesAgregar($id_webinar);
                        } else {
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return $this->webinarParticipantesAgregar($id_webinar);
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

                //DB::table('notas')->where('id_participante_curso', '=', $part_web->id)->delete();
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

    public function webinarProfesores($id) {
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
                $data['profesores'] = [];
                $data['webinar'] = Webinar::find($id);
                $web_prof = ProfesorWebinar::where('id_webinar', '=', $id)->get();
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

    public function webinarProfesoresAgregar($id) {
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

    public function webinarProfesoresGuardar($id_web, $id_profesor) {
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
                $webinar = Webinar::find($id_web);
                $profesor = Profesor::find($id_profesor);
                $existe = ProfesorWebinar::where('id_profesor', '=', $id_profesor)->where('id_webinar', '=', $id_web)->get();

                if($existe->count()) {
                    Session::set('error', 'Ya existe el registro en la base de datos');
                    return $this->webinarProfesoresAgregar($id_web);
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
                            return $this->webinarProfesoresAgregar($id_web);
                        } else {
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return $this->webinarProfesoresAgregar($id_web);
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


}
