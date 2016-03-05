@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Creación de Usuario
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
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

                {!! Form::open(array('method' => 'POST', 'action' => 'UsuariosController@store', 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}
                    
                    <div class="form-group">
                        {!!Form::label('nombre', '¿El nuevo usuario es Participante? ', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            @if($es_participante)
                                {!! Form::radio('es_participante', 'si', true) !!} Si <br/>
                                {!! Form::radio('es_participante', 'no', false) !!} No
                            @else
                                {!! Form::radio('es_participante', 'si', false) !!} Si <br/>
                                {!! Form::radio('es_participante', 'no', true) !!} No
                            @endif    
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre: ', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre',Session::get('nombre') ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('apellido', 'Apellido: ',  array('required', 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('apellido', Session::get('apellido'),array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('di', 'Documento de Identidad: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('documento_identidad', Session::get('documento_identidad'),array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group" id="ocultar">
                        {!!Form::label('rol', 'Rol(es): ',  array( 'class' => 'col-md-4 control-label'))!!}

{{--                            {{$rol}}--}}
                            <div class="col-sm-8">
                                @foreach($roles as $rol)
                                    @if ($rol == "Participante")
                                        <?php continue; ?>
                                    @else
                                        {!! Form::checkbox('id_rol[]', $rol, false) !!} {{$rol}} <br>
                                    @endif
                                @endforeach
                            </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('telefono', 'Teléfono de Fijo: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('telefono', Session::get('telefono'),array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('celular', 'Teléfono Móvil: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('celular', Session::get('celular'),array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group mostrar">
                        {!!Form::label('pais', 'Pais: ', array('class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8 pais">
                            <select class="form-control " id="id_pais" name="id_pais">
                                <option value="0"  selected="selected"> Seleccione un pais</option>
                                @foreach ($paises as $index=>$pais)
                                    <option value="{{$index}}">{{$pais}}</option>
                                @endforeach 
                            </select>
                        </div>
                    </div>

                    <div class="form-group localidad">
                        <div class="form-group">
                            {!!Form::label('estado', 'Estado:', array('class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                <!--{!! Form::select('id_estado', $estados, null, array( 'class' => 'form-control', 'id'=>'id_est'))!!}-->
                                <select class="form-control col-sm-8" id="id_est" name="id_est">
                                    <option value="0"  selected="selected"> Seleccione un estado</option>
                                    @foreach ($estados as $index=>$estado)
                                        <option value="{{$index}}">{{$estado}}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group localidad1">
                        <div class="form-group">
                            {!!Form::label('ciudad', 'Ciudad:', array('class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                <select class="form-control" id="ciudad" name="ciudad">

                                </select>                            
                            </div>
                        </div>
                    </div>
                    <div class="form-group localidad2">
                        <div class="form-group">
                            {!!Form::label('municipio', 'Municipio:', array('class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                <select class="form-control" id="municipio" name="municipio">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group localidad3">
                        <div class="form-group">
                            {!!Form::label('parroquia', 'Parroquia:', array('class' => 'col-md-4 control-label'))!!}
                            <div class="col-sm-8">
                                <select class="form-control" id="parroquia" name="parroquia">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('email', 'Correo electrónico: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::email('email', Session::get('email'), array('required','class' => 'form-control'))!!}
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
                    {{--<div class="form-group">--}}
                        {{--{!!Form::label('imagen', 'Imagen de perfil: ',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                        {{--<div class="col-sm-8">--}}
                            {{--{!!Form::file('imagen', '',array('class' => 'form-control'))!!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group mostrar">
                        {!!Form::label('email_alternativo', 'Correo electrónico alternativo: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::email('email_alternativo', Session::get('email_alternativo'), array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group mostrar" id="mostrar">
                        {!!Form::label('twitter', 'Usuario twitter: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('twitter', Session::get('twitter'), array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group mostrar" id="mostrar">
                        {!!Form::label('ocupacion', 'Ocupacion: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('ocupacion',Session::get('ocupacion'), array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group mostrar" id="mostrar">
                        {!!Form::label('titulo', 'Titulo de pregrado: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('titulo', Session::get('titulo'), array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group mostrar" id="mostrar">
                        {!!Form::label('univ', 'Universidad dónde obtuvo el título: ',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('univ', Session::get('univ'), array('class' => 'form-control'))!!}
                        </div>
                    </div>

                    <a href="{{URL::to("/")}}/usuarios" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Crear </button>
                {{--{!! Form::submit('Crear', array('class' => 'btn btn-success')) !!}--}}


                {!! Form::close() !!}
            </div>
        @endif
    </div>

    <script>
//        alert('holaaaas');

    </script>

@stop
