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
                        {!!Form::label('activo_carrusel', 'Webinar activo en el carrusel?:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            @if($activo_)
                                {!! Form::checkbox('activo_carrusel',null, true)!!}
                            @else
                                {!! Form::checkbox('activo_carrusel',null, $webinars->activo_carrusel)!!}
                            @endif
                        </div>
                    </div>
                    <div class="form-group" id="imagen_carrusel">
                        {!!Form::label('imagen_perfil', 'Imagen de Perfil: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8" id="borde">
                            @if($webinars->imagen_carrusel == null && !(Session::has('img_carg')))
                                {!!Form::file('file_perfil',['id' => 'file_perfil', 'accept' => 'image/jpeg'])!!}
                                {!!Form::hidden('img_carg',null)!!}
                                {!!Form::hidden('img_',null)!!}
                                {!!Form::hidden('file_viejo',$webinars->imagen_carrusel)!!}
                            @else
                                @if (Session::has('imagen'))
                                    {!!Form::file('file_perfil',['id' => 'file_perfil', 'accept' => 'image/jpeg'])!!}
                                    {!!Form::hidden('img_carg','yes',['id' => 'oculto'])!!}
                                    {!!Form::hidden('img_',null)!!}
                                    {!!Form::hidden('file_viejo',$webinars->imagen_carrusel)!!}
                                @else
                                    @if (Session::get('cortar') == "yes")
                                        <br>
                                        {!!Form::hidden('img_carg','yes')!!}
                                        {!!Form::hidden('img_','yes')!!}
                                        {!!Form::hidden('cortar','yes')!!}
                                        {!!Form::hidden('dir',$ruta)!!}
                                        {!!Form::hidden('file_viejo',$webinars->imagen_carrusel)!!}
                                        <img src="{{$ruta}}" id="imagen_cortada" width="150" height="150"><br><br>
                                        <a class="btn btn-success btn-xs" href="{{URL::to('/')}}/webinars/imagen/{{$webinars->id}}">Cambiar</a>
                                    @else
                                        <br>
                                        {!!Form::hidden('img_carg','yes')!!}
                                        {!!Form::hidden('img_','yes')!!}
                                        {!!Form::hidden('cortar',null)!!}
                                        <img src="{{URL::to('/')}}/images/images_carrusel/webinars/{{$webinars->imagen_carrusel}}" id="imagen_cortada" width="150" height="150"><br><br>
                                        <a class="btn btn-warning btn-sm" href="{{URL::to('/')}}/webinars/imagen/{{$webinars->id}}" title="Cambiar foto" data-toggle="tooltip" data-placement="bottom" aria-hidden="true" style="text-decoration: none">Cambiar</a>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                    <img class="" id="imagen2" src="" alt="">
                    {!!Form::hidden('file_viejo',$webinars->imagen_carrusel)!!}
                    <div class="form-group" id="descripcion_carrusel">
                        {!!Form::label('desc_carrusel_l', 'Titulo de la imagen en el carrusel:',  array( 'class' => 'col-md-4'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('descripcion_carrusel', $webinars->descripcion_carrusel, array('class' => 'form-control'))!!}
                        </div>
                    </div>
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
                    <a href="{{URL::to("/")}}/webinars" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Guardar </button>

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
                    <a href="{{URL::to('/')}}/webinars/{{$webinars->id}}/edit" class="pull-right"> <span class="glyphicon glyphicon-remove" style="color: #333;"></span> </a>
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
                    <a class="btn btn-default pull-left" href="{{URL::to('/')}}/webinars//{{$webinars->id}}edit"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    {!!Form::open(['url' => 'webinars/procesar/'.$webinars->id,  "method" => "post", "id" => "form_imagen"])!!}
                    <input type="hidden" class="" id="rutas" name="rutas">
                    <button type="submit" class="btn btn-success btn-success pull-right" id="aceptar" ><span class="glyphicon glyphicon-ok"></span> Aceptar</button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    {{--Fin Modal--}}

@stop
