<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'participantes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_usuario','nombre','apellido','documento_identidad','foto', 'telefono', 'direccion','celular', 'correo_alternativo', 'twitter', 'ocupacion', 'titulo_pregrado','universidad'];

    public function user(){
        return $this->belongsTo('App\User','id_usuario');
    }

    public function curso() {
        return $this->belongsToMany('App\Models\Curso', 'participante_cursos');
    }

//    public function user(){
//        return $this->hasOne('App\User','id');
//    }



}
