<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Preinscripcion extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'preinscripciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_curso','nombre', 'apellido', 'correo', 'tipo', 'documento_identidad', 'titulo', 'recibo'];

    public function curso(){
        return $this->belongsTo('App\Models\Curso','id_curso');
    }

    function getCursoName ($id){
        $nombrecurso = DB::table('cursos')
            ->join('preinscripciones', 'cursos.id', '=', 'preinscripciones.id_curso')
            ->select('cursos.nombre')
            ->where('cursos.id', '=', $id)
            ->get();

        foreach ($nombrecurso as $nombre) {
            $nombre->nombre;
        }
        return $nombre->nombre;
    }

    function getWebName ($id){
        $nombrecurso = DB::table('webinars')
            ->join('preinscripciones', 'webinars.id', '=', 'preinscripciones.id_curso')
            ->select('webinars.nombre')
            ->where('webinars.id', '=', $id)
            ->get();

        foreach ($nombrecurso as $nombre) {
            $nombre->nombre;
        }
        return $nombre->nombre;
    }

    function cantParticipantes($id){
        $cant = $this->where('id_curso','=', $id)->count();
        return $cant;
    }

//    function cantParticipantesWeb($id){
//        $cant = DB::table('webinars')
//            ->where('id', '=', $id)
//            ->count();
//        return $cant;
//    }


}
