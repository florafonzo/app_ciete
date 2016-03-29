@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Creación de Curso
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-xs-12 opciones_part2">
                @include('partials.mensajes')
                {!! Form::open(array('method' => 'POST', 'action' => 'CursosController@store', 'class' => 'form-horizontal col-md-10')) !!}

                <div class="form-group">
                    {!!Form::label('activo_carrusel', 'Curso activo en el carrusel?',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        @if($activo_)
                            {!! Form::checkbox('activo_carrusel',null, true)!!}
                        @else
                            {!! Form::checkbox('activo_carrusel',null, false)!!}
                        @endif
                    </div>
                </div>
                <div class="form-group" id="imagen_carrusel">
                    {!!Form::label('imagen_carrusel', 'Imagen carrusel: ',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8" id="borde">
                        @if(!(Session::has('img_carg')))
                            {!!Form::file('file_perfil',['id' => 'file_perfil', 'accept' => 'image/jpeg'])!!}
                            {!!Form::hidden('img_carg',null)!!}
                        @else
                            @if (Session::has('imagen'))
                                {!!Form::file('file_perfil',[ 'id' => 'file_perfil', 'accept' => 'image/jpeg'])!!}
                                {!!Form::hidden('img_carg','yes')!!}
                                {!!Form::hidden('img_',null)!!}
                            @else
                                @if (Session::has('cortar'))
                                    <br>
                                    {!!Form::hidden('img_carg','yes')!!}
                                    {!!Form::hidden('img_','yes')!!}
                                    {!!Form::hidden('cortar','yes')!!}
                                    {!!Form::hidden('dir',$ruta)!!}
                                    <img src="{{$ruta}}" id="imagen_cortada" width="150" height="150"><br><br>
                                    <a class="btn btn-success btn-xs" href="{{URL::to('/')}}/cursos/imagen">Cambiar</a>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
                <img class="" id="imagen2" src="" alt="">
                <div class="form-group" id="descripcion_carrusel">
                    {!!Form::label('desc_carrusel', 'Titulo de la imagen en el carrusel:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        {!! Form::text('descripcion_carrusel', Session::get('descripcion_carrusel'), array('class' => 'form-control'))!!}
                    </div>
                </div>

                <div class="form-group">
                    {!!Form::label('nombre', 'Nombre:', array( 'class' => 'col-md-4')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('nombre', Session::get('nombre') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('id_tipo', 'Tipo:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        {!! Form::select('id_tipo', $tipos, null, array('required','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('fecha_inicio', 'Fecha inicio:',  array( 'class' => 'col-md-4 '))!!}
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
                    {!!Form::label('id_modalidad_curso', 'Modalidad del curso:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        {!! Form::select('id_modalidad_curso', $modalidad_curso, null, array('required','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('secciones', 'Cantidad de secciones:',  array( 'class' => 'col-md-4 l'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('secciones', Session::get('secciones') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('mini', 'Cantidad de cupos MIN:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        {!!Form::text('mini', Session::get('min') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('maxi', 'Cantidad de cupos MAX:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        {!!Form::text('maxi', Session::get('max') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('especif', 'Especificaciones:',  array( 'class' => 'label_esp'))!!}
                    {!!Form::textarea('especificaciones', Session::get('especificaciones') ,array('required','class' => 'form-control ckeditor'))!!}
                </div>
                <div class="form-group">
                    {!!Form::label('costo', 'Costo:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        {!! Form::text('costo', Session::get('costo'), array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('modalidades_pago', 'Modalidades de pago:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        @foreach($modalidad_pago as $modalidad)
                           {!! Form::checkbox('modalidades_pago[]', $modalidad, false) !!} {{$modalidad}} <br>
                        @endforeach
                    </div>
                </div>
                <a href="{{URL::to("/")}}/cursos" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Crear </button>

                {!! Form::close() !!}
            </div>
        @endif
    </div>

    {{--Modal edición imagen de carrusel--}}
    <div class="modal fade" id="imagenModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <a href="{{URL::to('/')}}/cursos/create" class="pull-right"> <span class="glyphicon glyphicon-remove" style="color: #333;"></span> </a>
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
                    <a class="btn btn-default pull-left" href="{{URL::to('/')}}/cursos/create"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    {!!Form::open(['url' => 'cursos/procesar',  "method" => "post", "id" => ""])!!}
                        <input type="hidden" class="" id="rutas" name="rutas">
                        <button type="submit" class="btn btn-success btn-success pull-right" id="aceptar" ><span class="glyphicon glyphicon-ok"></span> Aceptar</button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    {{--Fin Modal--}}

@stop
