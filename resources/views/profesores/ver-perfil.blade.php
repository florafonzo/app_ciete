@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Datos de perfil
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                @if($datos->count())
                    {!! Form::open(array('method' => 'GET', 'action' => 'ParticipantesController@editarPerfil', 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}
                    {{--                    {!! Form::open(array('method' => 'GET', 'route' => array('participante.editarPerfil', Auth::user()->id), 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}--}}
                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre:', array( 'class' => 'col-md-4 ')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', $datos[0]->nombre ,array('disabled', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('apellido', 'Apellido:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('apellido', $datos[0]->apellido ,array('disabled','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('documento_identidad', 'Documento de Identidad:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('documento_identidad', $datos[0]->documento_identidad ,array('disabled','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('telefono', 'Teléfono Fijo:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('telefono', $datos[0]->telefono ,array('disabled','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('celular', 'Teléfono Móvil: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('celular', $datos[0]->celular, array('disabled', 'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('email', 'Correo electrónico:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::email('email', $email[0]->email, array('disabled','class' => 'form-control'))!!}
                        </div>
                    </div>

                    @if(Entrust::can('editar_perfil_profe'))
                        <div class="" style="text-align: center;">
                            <a href="{{URL::to('/')}}/profesor/perfil/{{Auth::user()->id}}/editar" type="button" class="btn btn-success" >Editar datos </a>
                        </div>
                    @endif

                    {!! Form::close() !!}
                @endif

            </div>
        @endif
    </div>

@stop