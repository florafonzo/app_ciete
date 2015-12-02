<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    protected $fillable = ['id_tipo','nombre','fecha','lugar','descripcion', 'area', 'dirigido_a', 'propositos', 'modalidad_estrategias', 'acreditacion', 'perfil','requerimientos_tec', 'perfil_egresado', 'instituciones_aval', 'aliados', 'plan_estudio','costo', 'modalidades_pago', 'imagen_carrusel', 'descripcion_carrusel', 'activo_carrusel'];

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

}