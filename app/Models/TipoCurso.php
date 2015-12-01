<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCurso extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tipo_cursos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre'];

    public function curso(){
        return $this->hasMany('App\Models\Curso','id');
    }

}
