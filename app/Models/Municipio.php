<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model {

    protected $table = 'municipios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_estado', 'municipio'];

//    public function estado(){
//        return $this->belongsTo('App\Models\Estado','id_estado');
//    }
//
//    public function parroquia(){
//        return $this->hasMany('App\Models\Parroquia','id');
//    }

}
