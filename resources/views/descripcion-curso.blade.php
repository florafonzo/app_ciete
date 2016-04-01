@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-12 col-xs-12">
            <div class="row">
                <div class="col-md-12 col-xs-12 descripcion_princ">
                    <h3>
                        <h2 class="centro">{{$curso->nombre}}</h2>
                    </h3>
                </div>
            </div>
            <div class="row">
                <img src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$curso->imagen_carrusel}}" class="img-responsive insta">
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                    <h3>Inicio: {{$inicio->format('d-m-Y')}}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                    {!!$curso->especificaciones!!}
                </div>
            </div>
        </div>
    </div>

@stop