@extends('layouts.layout')

@section('content')
<div class="row">

    <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
        <h3>
            Editar datos
        </h3>
    </div>

    @if (!(Auth::guest()))
    @include('partials.menu_usuarios')
    <div class="col-md-8 col-sm-8 opciones_part2">
        @include('partials.mensajes'){{--Errores--}}
        @if($datos->count())
        {!! Form::open(array('method' => 'PUT', 'route' => array('profesor.update', Auth::user()->id), 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}
        <div class="form-group">
            {!!Form::label('nombre', 'Nombre:', array( 'class' => 'col-md-4 ')) !!}
            <div class="col-sm-8">
                {!!Form::text('nombre', $datos[0]->nombre ,array('required', 'class' => 'form-control')) !!}
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('apellido', 'Apellido:',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8">
                {!!Form::text('apellido', $datos[0]->apellido ,array('required','class' => 'form-control'))!!}
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('documento_identidad', 'Documento de Identidad:',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8">
                {!!Form::text('documento_identidad', $datos[0]->documento_identidad ,array('required','class' => 'form-control'))!!}
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('telefono', 'Teléfono de Fijo:',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8">
                {!!Form::text('telefono', $datos[0]->telefono ,array('required','class' => 'form-control'))!!}
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('celular', 'Teléfono Móvil: ',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8">
                {!!Form::text('celular', $datos[0]->celular, array( 'class' => 'form-control'))!!}
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('email', 'Correo electrónico:',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8">
                {!! Form::email('email', $email[0]->email, array('required','class' => 'form-control'))!!}
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('password', 'Contraseña:',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8">
                {!! Form::password('password', array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="form-group">
            {!!Form::label('password1', 'Confirme su contraseña:',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8">
                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="form-group" id="perfil">
            {!!Form::label('imagen_perfil', 'Imagen de Perfil: ',  array( 'class' => 'col-md-4 '))!!}
            <div class="col-sm-8" id="borde">
                @if (Session::has('imagen'))
                    {!!Form::file('file_perfil',['required'=>'True', 'id' => 'file_perfil', 'accept' => 'image/jpeg'])!!}
                    {!!Form::hidden('img_carg','yes',['id' => 'oculto'])!!}
                    {!!Form::hidden('file_viejo',$foto)!!}
                @else
                    @if (Session::has('cortar'))
                        <br>
                        {!!Form::hidden('img_carg',null)!!}
                        {!!Form::hidden('cortar','yes')!!}
                        {!!Form::hidden('dir',$ruta)!!}
                        {!!Form::hidden('file_viejo',$foto)!!}
                        <img src="{{$ruta}}" id="imagen_cortada" width="150" height="150"><br><br>
                        <a class="btn btn-success btn-xs" id="cuadro" href="{{URL::to('/')}}/profesor/perfil/imagen">Cambiar</a>
                    @else
                        <br>
                        {!!Form::hidden('img_carg',null)!!}
                        {!!Form::hidden('cortar',null)!!}
                        <img src="{{URL::to('/')}}/images/images_perfil/{{$foto}}" id="imagen_cortada" width="150" height="150"><br><br>
                        <a class="btn btn-warning btn-sm" href="{{URL::to('/')}}/profesor/perfil/imagen" title="Cambiar foto" data-toggle="tooltip" data-placement="bottom" aria-hidden="true" style="text-decoration: none">Cambiar</a>
                    @endif
                @endif
            </div>
        </div>
        <img class="" id="imagen2" src="" alt="">
        {!!Form::hidden('file_viejo',$foto)!!}
        @if(Entrust::can('editar_perfil_profe'))
            <a href="{{URL::to("/")}}/profesor/perfil" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
            {!! Form::submit('Guardar', array('class' => 'btn btn-success')) !!}
        @endif
        {!! Form::close() !!}
        @endif

    </div>
    @endif
</div>

{{--Modal edición imagen de perfil--}}
<div class="modal fade" id="imagenModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <a href="{{URL::to('/')}}/profesor/perfil/{{Auth::user()->id}}/editar" class="pull-right"> <span class="glyphicon glyphicon-remove" style="color: #333;"></span> </a>
                <h4> Edición de imagen</h4>
            </div>
            <div class="modal-body">

                <div class="content2 esconder" id="" style="display: none; ">
                    <div class="component ">
                        <div class="overlay ">
                            <div class="overlay-inner ">
                            </div>
                        </div>
                        <img class="resize-image" id="imagen" src="" alt="image for resizing">
                    </div>

                </div>
                <div id="pepe">
                </div>
                <br>
                <div style="text-align: center">
                    <button class="btn js-crop" type="button"><i class="fa fa-crop"></i> Cortar </button>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default pull-left" href="{{URL::to('/')}}/profesor/perfil/{{Auth::user()->id}}/editar"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                {!!Form::open(['url' => 'profesor/perfil/procesar/',  "method" => "post", "id" => "form_imagen"])!!}
                    <input type="hidden" class="" id="rutas" name="rutas">
                    <button type="submit" class="btn btn-success btn-success pull-right" id="aceptar" ><span class="glyphicon glyphicon-ok"></span> Aceptar</button>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
{{--Fin Modal--}}




@stop