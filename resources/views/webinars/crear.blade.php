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
            <div class="col-md-8 col-xs-12 opciones_part2">
                @include('partials.mensajes')
                {!! Form::open(array('method' => 'POST', 'action' => 'WebinarsController@store', 'files' => true,'class' => 'form-horizontal col-md-10')) !!}

                <div class="form-group">
                    {!!Form::label('nombre', 'Nombre:', array( 'class' => 'col-md-4')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('nombre', Session::get('nombre') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('fecha_inicio', 'Fecha inicio:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!!Form::input('date', 'fecha_inicio', Session::get('fecha_inicio') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('fecha_fin', 'Fecha fin:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!!Form::input('date', 'fecha_fin', Session::get('fecha_fin') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('secciones', 'Cantidad de secciones:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('secciones', Session::get('secciones') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('mini', 'Cantidad de cupos MIN:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('mini', Session::get('min') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('maxi', 'Cantidad de cupos MAX:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('maxi', Session::get('max') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('especif', 'Especificaciones:',  array( 'class' => 'label_esp'))!!}
                    {!!Form::textarea('especificaciones', Session::get('especificaciones') ,array('required','class' => 'form-control ckeditor'))!!}
                </div>
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('duracion', 'Duracion del curso en horas: ', array( 'class' => 'col-md-4 control-label')) !!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!!Form::text('duracion',Session::get('duracion') ,array('required', 'class' => 'form-control')) !!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('lugar', 'Lugar:',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!!Form::text('lugar', Session::get('lugar') ,array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('desc', 'Descripción:',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!!Form::textarea('descripcion', Session::get('descripcion') ,array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('link', 'Url:',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::text('link', Session::get('link'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">
                    {!!Form::label('activo_carrusel', 'Webinar activo en el carrusel?:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!! Form::checkbox('activo_carrusel',null, false)!!}
                    </div>
                </div>
                <div class="form-group" id="imagen_carrusel">
                    {!!Form::label('imagen_carrusel', 'Imagen carrusel:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!! Form::file('imagen_carrusel', null, array('class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group" id="descripcion_carrusel">
                    {!!Form::label('desc_carrusel', 'Titulo de la imagen en el carrusel:',  array( 'class' => 'col-md-4'))!!}
                    <div class="col-sm-8">
                        {!! Form::text('descripcion_carrusel', Session::get('descripcion_carrusel'), array('class' => 'form-control'))!!}
                    </div>
                </div>

                <a href="{{URL::to("/")}}/webinars" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Crear </button>
                {!! Form::close() !!}
            </div>
        @endif
    </div>

@stop
