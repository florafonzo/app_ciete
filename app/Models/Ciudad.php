<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model {

    protected $table = 'ciudades';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_estado', 'ciudad', 'capital'];

//    public function estado(){
//        return $this->belongsTo('App\Models\Estado','id_estado');
//    }

}
