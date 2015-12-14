<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipanteWebinar extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'participante_webinars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_participante','id_webinar'];

}
