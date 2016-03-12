@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Edición de Webinar
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')

            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                @if($webinars->count())
                    {!! Form::open(array('method' => 'PUT', 'route' => array('webinars.update', $webinars->id),'files' => true, 'class' => 'form-horizontal col-md-12')) !!}

                    <div class="form-group">
                        {!!Form::label('nombre_l', 'Nombre:', array( 'class' => 'col-md-4')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', $webinars->nombre ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fechaI_l', 'Fecha inicio:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_inicio', $webinars->fecha_inicio ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fechaF_l', 'Fecha fin:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_fin', $webinars->fecha_fin ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('secciones_l', 'Cantidad de secciones:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('secciones', $webinars->secciones ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('mini_l', 'Cantidad de cupos MIN:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('mini', $webinars->min ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('maxi_l', 'Cantidad de cupos MAX:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('maxi', $webinars->max ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('especif', 'Especificaciones:',  array( 'class' => 'label_esp'))!!}
                        {!!Form::textarea('especificaciones', $webinars->especificaciones ,array('required','class' => 'form-control ckeditor'))!!}
                    </div>
                    {{--<div class="form-group">--}}
                        {{--{!!Form::label('duracion_l', 'Duracion del curso en horas: ', array( 'class' => 'col-md-4 control-label')) !!}--}}
                        {{--<div class="col-sm-8">--}}
                            {{--{!!Form::text('duracion',$webinars->duracion ,array('required', 'class' => 'form-control')) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--{!!Form::label('lugar_l', 'Lugar:',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                        {{--<div class="col-sm-8">--}}
                            {{--{!!Form::text('lugar', $webinars->lugar ,array('required','class' => 'form-control'))!!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--{!!Form::label('desc_l', 'Descripción:',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                        {{--<div class="col-sm-8">--}}
                            {{--{!!Form::textarea('descripcion', $webinars->descripcion ,array('required','class' => 'form-control'))!!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--{!!Form::label('link_l', 'Url:',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                        {{--<div class="col-sm-8">--}}
                            {{--{!! Form::text('link', $webinars->link, array('required','class' => 'form-control'))!!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        {!!Form::label('activo_carrusel', 'Webinar activo en el carrusel?:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!! Form::checkbox('activo_carrusel',null, $webinars->activo_carrusel)!!}
                        </div>
                    </div>
                    <div class="form-group" id="imagen_carrusel">
                        {!!Form::label('imagen_carrusel_l', 'Imagen carrusel:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!! Form::file('imagen_carrusel', $webinars->imagen_carrusel, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group" id="descripcion_carrusel">
                        {!!Form::label('desc_carrusel_l', 'Titulo de la imagen en el carrusel:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('descripcion_carrusel', $webinars->descrpcion_carrusel, array('class' => 'form-control'))!!}
                        </div>
                    </div>

                    <a href="{{URL::to("/")}}/webinars" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Guardar </button>

                    {!! Form::close() !!}
                @endif
            </div>
        @endif
    </div>

@stop
