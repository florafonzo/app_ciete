<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model {

    protected $table = 'estados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estado', 'iso-3166-2I', 'estadoscol'];


    public function ciudad(){
        return $this->hasMany('App\Models\Ciudad','id');
    }

    public function municipio(){
        return $this->hasMany('App\Models\Municipio','id');
    }
}

