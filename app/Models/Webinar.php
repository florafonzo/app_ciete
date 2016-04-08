<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webinar extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'webinars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cupos','webinar_activo','nombre','fecha_inicio','fecha_fin','duracion', 'lugar', 'descripcion','link'];


    public function participante() {
        return $this->belongsToMany('App\Models\Participante', 'participante_webinars');
    }

    public function profesor() {
        return $this->belongsToMany('App\Models\Profesor', 'profesor_webinars');
    }

//    public function preinscripcion(){
//        return $this->hasMany('App\Models\Preinscripcion','id');
//    }

    public function maxCuposWeb($id){

        $secciones=  $this->where('id', '=',$id)
            ->pluck('secciones');

        $max= $this->where('id', '=', $id)
            ->pluck('max');

        return $secciones*$max;
    }

}
