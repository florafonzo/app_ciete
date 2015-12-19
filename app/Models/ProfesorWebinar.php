<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfesorWebinar extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profesor_webinars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_profesor','id_webinar'];

}
