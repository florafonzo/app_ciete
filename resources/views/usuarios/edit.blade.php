@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Bienvenido {{Auth::user()->nombre}} {{ Auth::user()->apellido }}
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('layouts.menu_usuarios')
            {{--<div class="col-md-4 col-sm-4 opciones_part">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-6 col-sm-6 col-md-offset-3">--}}
                        {{--<img src="{{URL::to('/')}}/images/foto_participante.png">--}}
                    {{--</div>--}}

                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-12 col-sm-12 menu_part">--}}
                        {{--<ul class="nav nav-pills nav-stacked">--}}
                            {{--<li class="active menu_usuarios">--}}
                                {{--<a href="{{URL::to('/usuarios')}}"> Usuarios </a>--}}
                            {{--</li>--}}
                            {{--<li class="menu_usuarios">--}}
                                {{--<a href="#"> Lista de cursos </a>--}}
                            {{--</li>--}}
                            {{--<li class="menu_usuarios">--}}
                                {{--<a href="#"> Carrusel </a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-8 col-sm-8 opciones_part2">

                @if (count($errors) > 0)
                    <div class="row">
                        <div class="errores ">
                            <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>
                            <ul class="lista_errores">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if ($errores != '')
                    <div class="row">
                        <div class="errores ">
                            <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>
                            <ul class="lista_errores">
                                {{--@foreach ($errores->all() as $error)--}}
                                <li>{{ $errores }}</li>
                                {{--@endforeach--}}
                            </ul>
                        </div>
                    </div>
                @endif
                @if($usuarios->count())
                    {!! Form::open(array('method' => 'PUT', 'route' => array('usuarios.update', $usuarios->id), 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}

                    @if($es_participante)
                        <div class="form-group">
                            {!!Form::label('rol_actual', 'Rol actual: ', array( 'class' => 'col-md-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!!Form::text('rol_actual', $rol[0]->name ,array('disabled', 'class' => 'form-control')) !!}

                                {{--{!! Form::radio('es_participante', 'si', false) !!} Si <br/>--}}
                                {{--{!! Form::radio('es_participante', 'no', true) !!} No--}}
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', $usuarios->nombre ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('apellido', 'Apellido',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('apellido', $usuarios->apellido ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('documento_identidad', 'Documento de Identidad',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('documento_identidad', $datos_usuario->documento_identidad ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    @if(!($es_participante))
                        <div class="form-group">
                            {!!Form::label('rol', 'Rol',  array( 'class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                @foreach($roles as $role)
                                    @if ($role == "Participante")
                                        <?php continue; ?>
                                    @else
                                        {!! Form::checkbox('id_rol[]', $role, false) !!} {{$role}} <br>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        {!!Form::label('telefono', 'Teléfono de Fijo',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('telefono', $datos_usuario->telefono ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('celular', 'Teléfono Móvil: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('celular', $datos_usuario->celular, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('email', 'Correo electrónico',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::email('email', $usuarios->email, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('password', 'Contraseña',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::password('password', array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('password1', 'Confirme su contraseña',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::password('password_confirmation', array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('imagen', 'Imagen de perfil: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::file('imagen', $datos_usuario->foto, array('class' => 'form-control'))!!}
                        </div>
                    </div>

                    @if($es_participante)
                        <div class="form-group">
                            {!!Form::label('correo_alternativo', 'Correo electrónico alternativo: ',  array( 'class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                {!! Form::email('correo_alternativo', $datos_usuario->correo_alternativo, array('class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group" >
                            {!!Form::label('twitter', 'Usuario twitter: ',  array( 'class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                {!! Form::text('twitter', $datos_usuario->twitter, array('class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group" >
                            {!!Form::label('ocupacion', 'Ocupacion: ',  array( 'class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                {!! Form::text('ocupacion', $datos_usuario->ocupacion, array('class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group" >
                            {!!Form::label('titulo', 'Titulo de pregrado: ',  array( 'class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                {!! Form::text('titulo', $datos_usuario->titulo_pregrado, array('class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!!Form::label('univ', 'Universidad dónde obtuvo el título: ',  array( 'class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                {!! Form::text('univ', $datos_usuario->universidad, array('class' => 'form-control'))!!}
                            </div>
                        </div>
                    @endif
                    {!! Form::submit('Editar', array('class' => 'btn btn-success')) !!}

                    {!! Form::close() !!}

                @endif
            </div>
        @endif
    </div>

@stop
