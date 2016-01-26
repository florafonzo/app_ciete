<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model {

    protected $table = 'estados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estado', 'iso_31662'];


//    public function ciudad(){
//        return $this->hasMany('App\Models\Ciudad','id');
//    }
//
//    public function municipio(){
//        return $this->hasMany('App\Models\Municipio','id');
//    }
}

