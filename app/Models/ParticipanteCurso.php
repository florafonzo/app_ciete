<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipanteCurso extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'participante_cursos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_participante','id_curso', 'seccion'];




}
