<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreinscripcionRequest;

use Illuminate\Http\Request;

use App\Models\Curso as curso;
use DateTime;
use Auth;
use App\Models\TipoCurso;
use App\Models\Preinscripcion as preinscripcion;
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

                return view('preinscripcion.preinscripcion', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function mostrarPreinscripcion(){

        $data['errores'] = '';
        $data['show'] = false;
        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

        return view('preinscripcion.principal', $data);
    }

    public function storePreinscripcion(PreinscripcionRequest $request){

        try {

            $preins = new preinscripcion;

            $cours = new curso;

            /*
            if ($request->hasFile('cedula') && $request->hasFile('titulo')) {
                    $imagen = $request->file('cedula');
                    $imagen2 = $request->file('cedula');
                } else {
                    $imagen = '';
                    $imagen2= '';
            }*/

            $data['errores'] = '';

            $id = Input::get('id_curso');

            $max= $cours->maxCuposCurso($id); //obtengo el máximo número de participantes que puede tener un curso

            $cant = $preins->cantParticipantes($id);

            if($cant < $max){
                $create2 = Preinscripcion::findOrNew($request->id);
                $create2->id_curso = $request->id_curso;
                $create2->nombre = $request->nombre;
                $create2->apellido = $request->apellido;
                $create2->documento_identidad = $request->cedula;
                $create2->titulo = $request->titulo;
                $create2->email = $request->email;

                $create2->save();

                $data['nombre'] = $request->nombre;
                $data['apellido'] = $request->apellido;

                $data['curso'] = $preins->getCursoName($id); // aquí se retorna el nombre del curso 

                $data['email'] = $request->email;

                Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                    $message->subject('Contacto:'.$data['nombre'])
                        ->to('ciete.app@gmail.com', 'CIETE')
                        ->replyTo($data['email']);
                });
                $data['show'] = true;
                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');
                return view('preinscripcion.principal', $data);

            }else{

                $data['show'] = false;

                $curso = Curso::find($id);
                $curso->activo_preinscripcion = false;//Se desactiva el curso
                $curso->save(); // se guarda

                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');
                return view('preinscripcion.principal', $data);

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
                $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }

                return view('preinscripcion.preinscripcion', $data);

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
                $data['cursos'] = Curso::orderBy('created_at')->get(); // Se obtienen todos los cursos con sus datos

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }

                return view('preinscripcion.preinscripcion', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
}
