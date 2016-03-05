<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfesorCurso extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profesor_cursos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_profesor','id_curso'];

}
