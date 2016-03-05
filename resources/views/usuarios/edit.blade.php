@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Edición de Usuario
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')

            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}

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
                                @foreach($roles as $index => $role)
                                    @if($rols[$index] == true)
                                        {!! Form::checkbox('id_rol[]', $role, true) !!} {{$role}} <br>
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
                    {{--<div class="form-group">--}}
                        {{--{!!Form::label('imagen', 'Imagen de perfil: ',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                        {{--<div class="col-sm-8">--}}
                            {{--{!!Form::file('imagen', $datos_usuario->foto, array('class' => 'form-control'))!!}--}}
                        {{--</div>--}}
                    {{--</div>--}}

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
                        <a href="{{URL::to("/")}}/usuarios" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Guardar </button>

{{--                    {!! Form::submit('Editar', array('class' => 'btn btn-success')) !!}--}}


                    {!! Form::close() !!}

                @endif
            </div>
        @endif
    </div>

@stop
