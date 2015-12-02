<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profesores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_usuario','nombre','apellido','documento_identidad','foto', 'telefono', 'celular'];

    public function user(){
        return $this->belongsTo('App\User','id_usuario');
    }

    public function curso() {
        return $this->belongsToMany('App\Models\Curso', 'profesor_cursos');
    }

}
