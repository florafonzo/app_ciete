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

                {!! Form::open(array('method' => 'POST', 'action' => 'UsuariosController@store', 'class' => 'form-horizontal col-md-10')) !!}

                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre: ', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', '' ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('apellido', 'Apellido: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('apellido', '',array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('di', 'Documento de Identidad: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('documento_identidad', '',array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('rol', 'Rol(es): ',  array( 'class' => 'col-md-4 control-label'))!!}

{{--                            {{$rol}}--}}
                            <div class="col-sm-8">
                                @foreach($roles as $rol)
                                    {!! Form::checkbox('id_rol[]', $rol, false) !!} {{$rol}} <br>
                                @endforeach
                            </div>

                    </div>
                    <div class="form-group">
                        {!!Form::label('telefono', 'Telefono de Contacto: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('telefono', '',array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('email', 'Correo electrónico: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::email('email', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('password', 'Contraseña: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::password('password', array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('password1', 'Confirme su contraseña: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::password('password_confirmation', array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    {!! Form::submit('Crear', array('class' => 'btn btn-success')) !!}

                {!! Form::close() !!}
            </div>
        @endif
    </div>

@stop
