<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreinscripcionRequest;

use App\Models\Curso;
use App\Models\Preinscripcion;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

//use App\Models\Curso as curso;
use DateTime;
use Auth;
use Exception;
use App\Models\TipoCurso;
//use App\Models\Preinscripcion as preinscripcion;
use DB;
use Input;
use Mail;

class PreinscripcionController extends Controller {

    //
    public function index(){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['cursos'] = Curso::where('curso_activo', '=', true)
                                        ->where('fecha_inicio', '>', new DateTime('today'))
                                        ->orderBy('created_at')->get();

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function indexWeb(){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinars'] = Webinar::where('webinar_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['webinars'] as $web) {
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function mostrarPreinscripcionCurso(){

        $data['errores'] = '';
        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

        return view('preinscripcion.curso', $data);
    }

    public function mostrarPreinscripcionWebinar(){

        $data['errores'] = '';
        $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

        return view('preinscripcion.webinar', $data);
    }

    public function storePreinscripcionCurso(PreinscripcionRequest $request){

        try {

            $preins = new Preinscripcion();

            $cours = new Curso();

            /*
            if ($request->hasFile('cedula') && $request->hasFile('titulo')) {
                    $imagen = $request->file('cedula');
                    $imagen2 = $request->file('cedula');
                } else {
                    $imagen = '';
                    $imagen2= '';
            }*/

            $data['errores'] = '';
            $id = Input::get('curso');
            if($id == 0){
                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
                Session::set('error', 'Debe seleccionar un curso de la lista');
                return view('preinscripcion.curso', $data);
            }
            $max= $cours->maxCuposCurso($id); //obtengo el máximo número de participantes que puede tener un curso
            $cant = $preins->cantParticipantes($id);

            if($cant < $max){
                $hay = Preinscripcion::all();
                if($hay->count()){
                    $existe = Preinscripcion::where('id_curso','=', $request->curso)
                        ->where('tipo', '=', 'curso')
                        ->where('nombre', '=', $request->nombre)
                        ->where('apellido', '=', $request->apellido)->get();
                    if(!($existe->count())) {
                        $create2 = Preinscripcion::findOrNew($request->id);
                        $create2->id_curso = $request->curso;
                        $create2->nombre = $request->nombre;
                        $create2->apellido = $request->apellido;
                        $create2->documento_identidad = $request->cedula;
                        $create2->titulo = $request->titulo;
                        $create2->recibo = $request->recibo;
                        $create2->email = $request->email;
                        $create2->tipo = 'curso';

                        $create2->save();

                        $data['nombre'] = $request->nombre;
                        $data['apellido'] = $request->apellido;

                        $data['curso'] = $preins->getCursoName($id); // aquí se retorna el nombre del curso

                        $data['email'] = $request->email;

                        Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                            $message->subject('CIETE - Preinscripcion')
                                ->to($data['email'], 'CIETE')
                                ->replyTo($data['email']);
                        });
                        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');

                        Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a su correo. Recuerde revisar su carpeta de Spam');
                        return view('preinscripcion.curso', $data);
                    }else{
                        $curso = Curso::find($id);
                        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

                        Session::set('error', 'Usted ya se encuentra preinscrito en el curso '.$curso->nombre);
                        return view('preinscripcion.curso', $data);
                    }

                }else{
                    $create2 = Preinscripcion::findOrNew($request->id);
                    $create2->id_curso = $request->curso;
                    $create2->nombre = $request->nombre;
                    $create2->apellido = $request->apellido;
                    $create2->documento_identidad = $request->cedula;
                    $create2->titulo = $request->titulo;
                    $create2->email = $request->email;
                    $create2->recibo = $request->recibo;
                    $create2->tipo = 'curso';

                    $create2->save();

                    $data['nombre'] = $request->nombre;
                    $data['apellido'] = $request->apellido;

                    $data['curso'] = $preins->getCursoName($id); // aquí se retorna el nombre del curso

                    $data['email'] = $request->email;

                    Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                        $message->subject('CIETE - Preinscripcion')
                            ->to($data['email'], 'CIETE')
                            ->replyTo($data['email']);
                    });
                    $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');

                    Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a nsu correo. Recuerde revisar su carpeta de Spam');
                    return view('preinscripcion.curso', $data);
                }
            }else{
                $curso = Curso::find($id);
                $curso->activo_preinscripcion = false;//Se desactiva el curso
                $curso->save(); // se guarda
                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

                Session::set('error', 'Ya no quedan cupos disponibles para el curso '.$curso->nombre);
                return view('preinscripcion.curso', $data);

            }

        } catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function storePreinscripcionWebinar(PreinscripcionRequest $request){

        try {

            $preins = new Preinscripcion();
            $cours = new Webinar();
            $data['errores'] = '';
            $id = Input::get('curso');
            if($id == 0){
                $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
                Session::set('error', 'Debe seleccionar un webinar de la lista');
                return view('preinscripcion.webinar', $data);
            }
            $max= $cours->maxCuposWeb($id); //obtengo el máximo número de participantes que puede tener un curso
            $cant = $preins->cantParticipantes($id);

            if($cant < $max){
                $hay = Preinscripcion::all();
                if($hay->count()){
                    $existe = Preinscripcion::where('id_curso','=', $request->curso)
                        ->where('tipo', '=', 'webinar')
                        ->where('nombre', '=', $request->nombre)
                        ->where('apellido', '=', $request->apellido)->get();
                    if(!($existe->count())) {
                        $create2 = Preinscripcion::findOrNew($request->id);
                        $create2->id_curso = $request->curso;
                        $create2->nombre = $request->nombre;
                        $create2->apellido = $request->apellido;
                        $create2->documento_identidad = $request->cedula;
                        $create2->titulo = $request->titulo;
                        $create2->email = $request->email;
                        $create2->recibo = $request->recibo;
                        $create2->tipo = 'webinar';

                        $create2->save();

                        $data['nombre'] = $request->nombre;
                        $data['apellido'] = $request->apellido;

                        $data['curso'] = $preins->getWebName($id); // aquí se retorna el nombre del webinar

                        $data['email'] = $request->email;

                        Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                            $message->subject('CIETE - Preinscripcion')
                                ->to($data['email'], 'CIETE')
                                ->replyTo($data['email']);
                        });
                        $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');

                        Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a su correo. Recuerde revisar su carpeta de Spam');
                        return view('preinscripcion.webinar', $data);
                    }else{
                        $web = Webinar::find($id);
                        $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

                        Session::set('error', 'Usted ya se encuentra preinscrito en el webinar '.$web->nombre);
                        return view('preinscripcion.webinar', $data);
                    }

                }else{
                    $create2 = Preinscripcion::findOrNew($request->id);
                    $create2->id_curso = $request->curso;
                    $create2->nombre = $request->nombre;
                    $create2->apellido = $request->apellido;
                    $create2->documento_identidad = $request->cedula;
                    $create2->titulo = $request->titulo;
                    $create2->email = $request->email;
                    $create2->recibo = $request->recibo;
                    $create2->tipo = 'webinar';

                    $create2->save();

                    $data['nombre'] = $request->nombre;
                    $data['apellido'] = $request->apellido;

                    $data['curso'] = $preins->getCursoName($id); // aquí se retorna el nombre del webinar

                    $data['email'] = $request->email;

                    Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                        $message->subject('CIETE - Preinscripcion')
                            ->to($data['email'], 'CIETE')
                            ->replyTo($data['email']);
                    });
                    $data['cursos'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');

                    Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a nsu correo. Recuerde revisar su carpeta de Spam');
                    return view('preinscripcion.webinar', $data);
                }
            }else{
                $web = Webinar::find($id);
                $web->activo_preinscripcion = false;//Se desactiva el curso
                $web->save(); // se guarda
                $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

                Session::set('error', 'Ya no quedan cupos disponibles para el curso '.$web->nombre);
                return view('preinscripcion.webinar', $data);

            }

        } catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function activarPreinscripcion($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {// Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea eliminar
                $curso = Curso::find($id);
                //Se desactiva el curso
                $curso->activo_preinscripcion = true;
                $curso->save(); // se guarda

                $data['errores'] = '';
                $data['cursos'] = Curso::where('curso_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }


                return view('preinscripcion.preinscripcion-curso', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function desactivarPreinscripcion($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('desactivar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea eliminar
                $curso = Curso::find($id);
                //Se desactiva el curso
                $curso->activo_preinscripcion = false;
                $curso->save(); // se guarda

                $data['errores'] = '';
                $data['cursos'] = Curso::where('curso_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }


                return view('preinscripcion.preinscripcion-curso', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function activarPreinscripcionWeb($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {// Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea eliminar
                $webinar = Webinar::find($id);
                //Se desactiva el curso
                $webinar->activo_preinscripcion = true;
                $webinar->save(); // se guarda

                $data['errores'] = '';
                $data['webinars'] = Webinar::where('webinar_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['webinars'] as $web) {
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function desactivarPreinscripcionWeb($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('desactivar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del webinar que se desea eliminar
                $webinar = Webinar::find($id);
                //Se desactiva el curso
                $webinar->activo_preinscripcion = false;
                $webinar->save(); // se guarda

                $data['errores'] = '';
                $data['webinars'] = Webinar::where('webinar_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['webinars'] as $web) {
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
}
