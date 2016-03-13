<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Curso;
use DateTime;
use Auth;
use App\Models\TipoCurso;
use DB;

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

	public function mostrarPreinscripcion(){

        $data['errores'] = '';
        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
       
		return view('preinscripcion.principal', $data);
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
