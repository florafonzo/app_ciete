<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadPago extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'modalidad_pagos';

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
