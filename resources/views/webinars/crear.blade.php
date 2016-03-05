@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Creación de Webinar
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')

            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                {{--@if (count($errors) > 0)--}}
                    {{--<div class="row">--}}
                        {{--<div class="errores ">--}}
                            {{--<strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>--}}
                            {{--<ul class="lista_errores">--}}
                                {{--@foreach ($errors->all() as $error)--}}
                                    {{--<li>{{ $error }}</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
                {{--@if ($errores != '')--}}
                    {{--<div class="row">--}}
                        {{--<div class="errores ">--}}
                            {{--<strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>--}}
                            {{--<ul class="lista_errores">--}}
                                {{--@foreach ($errores->all() as $error)--}}
                                {{--<li>{{ $errores }}</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
                {{--@if($tipos->count())--}}
                {!! Form::open(array('method' => 'POST', 'action' => 'WebinarsController@store', 'files' => true,'class' => 'form-horizontal col-md-10')) !!}

                <div class="form-group">
                    {!!Form::label('nombre', 'Nombre', array( 'class' => 'col-md-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('nombre', Session::get('nombre') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('fecha_inicio', 'Fecha inicio',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::input('date', 'fecha_inicio', Session::get('fecha_inicio') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('fecha_fin', 'Fecha fin',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::input('date', 'fecha_fin', Session::get('fecha_fin') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('duracion', 'Duracion del curso en horas: ', array( 'class' => 'col-md-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('duracion',Session::get('duracion') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('cupos', 'Cantidad de cupos',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('cupos', Session::get('cupos') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('lugar', 'Lugar',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('lugar', Session::get('lugar') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('desc', 'Descripción',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::textarea('descripcion', Session::get('descripcion') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('link', 'Url',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!! Form::text('link', Session::get('link'), array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('activo_carrusel', 'Curso activo en el carrusel?',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::checkbox('activo_carrusel',null, false)!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group" id="imagen_carrusel">--}}
                    {{--{!!Form::label('imagen_carrusel', 'Imagen carrusel',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::file('imagen_carrusel', null, array('class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group" id="descripcion_carrusel">--}}
                    {{--{!!Form::label('desc_carrusel', 'Titulo de la imagen en el carrusel',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::text('descripcion_carrusel', Session::get('descripcion_carrusel'), array('class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}

                <a href="{{URL::to("/")}}/webinars" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Crear </button>
{{--                {!! Form::submit('Crear', array('class' => 'btn btn-success')) !!}--}}

                {!! Form::close() !!}
                {{--@endif--}}
            </div>
        @endif
    </div>

@stop
