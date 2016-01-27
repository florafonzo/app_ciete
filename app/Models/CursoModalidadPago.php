<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursoModalidadPago extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'curso_modalidad_pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_curso','id_modalidad_pago'];



}
