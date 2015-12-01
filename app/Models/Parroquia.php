<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model {

    protected $table = 'parroquias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_municipio', 'parroquia'];

    public function municipio(){
        return $this->belongsTo('App\Models\Municipio','id_municipio');
    }

}
