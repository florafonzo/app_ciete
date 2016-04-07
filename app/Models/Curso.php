<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Curso extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cursos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_tipo','id_modalidad_pago','id_modalidad_curso','curso_activo','cupos','nombre','fecha_inicio','fecha_fin', 'especificaciones','costo' , 'imagen_carrusel', 'descripcion_carrusel', 'activo_carrusel'];

    public function tipo_curso(){
        return $this->belongsTo('App\Models\TipoCurso','id_tipo');
    }

    public function participante() {
        return $this->belongsToMany('App\Models\Participante', 'participante_cursos');
    }

    public function profesor() {
        return $this->belongsToMany('App\Models\Profesor', 'profesor_cursos');
    }

    public function preinscripcion(){
        return $this->hasMany('App\Models\Preinscripcion','id');
    }

    public function modalidad_curso(){
        return $this->belongsTo('App\Models\ModalidadCurso','id_modalidad_curso');
    }

    public function modalidad_pago(){
        return $this->belongsToMany('App\Models\ModalidadPago','curso_modalidad_pago');
    }

    public function maxCuposCurso ($id){

        $secciones=  $this->where('id', '=',$id)
            ->pluck('secciones');

        $max= $this->where('id', '=', $id)
            ->pluck('max');

        return $secciones*$max;
    }

    function getDiplos(){
        $diplo = DB::table('cursos')
            ->join('tipo_cursos', 'cursos.id_tipo', '=', 'tipo_cursos.id')
            ->select('cursos.id', 'cursos.descripcion_carrusel', 'cursos.imagen_carrusel')
            ->where('tipo_cursos.nombre', '=', 'Diplomado')
            ->where('cursos.activo_carrusel', '=', true)
            ->where('cursos.curso_activo', '=', true)
            ->get();

        return $diplo;
    }

    function getCaps(){
        $caps = DB::table('cursos')
            ->join('tipo_cursos', 'cursos.id_tipo', '=', 'tipo_cursos.id')
            ->select('cursos.id', 'cursos.descripcion_carrusel', 'cursos.imagen_carrusel')
            ->where('tipo_cursos.nombre', '=', 'Cápsula')
            ->where('cursos.activo_carrusel', '=', true)
            ->where('cursos.curso_activo', '=', true)
            ->get();

        return $caps;
    }

    public static function getCursos(){
        $data['caps'] = [];
        $data['diplos'] = [];
        $data['webis'] = [];

        $data['cursos'] = DB::table('cursos')
            ->select('id', 'nombre')
            ->where('curso_activo', '=', true)
            ->orderBy('nombre')
            ->get();

        $data['webis'] = DB::table('webinars')
            ->select('id', 'nombre')
            ->where('activo_carrusel', '=', true)
            ->where('webinar_activo', '=', true)
            ->orderBy('nombre')
            ->get();

        return $data;
    }

}
