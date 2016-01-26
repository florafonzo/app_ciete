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
                            {{--<li class="menu_usuarios">--}}
                                {{--<a href="{{URL::to('/usuarios')}}"> Usuarios </a>--}}
                            {{--</li>--}}
                            {{--<li class="active menu_usuarios">--}}
                                {{--<a href="{{URL::to('/cursos')}}"> Lista de cursos </a>--}}
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
                @if($cursos->count())
                    {!! Form::open(array('method' => 'PUT', 'route' => array('cursos.update', $cursos->id),'files' => true, 'class' => 'form-horizontal col-md-12')) !!}

                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', $cursos->nombre ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('id_tipo', 'Tipo',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::select('id_tipo', $tipos, $tipo, array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fecha', 'Fecha',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_inicio', $cursos->fecha_inicio ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fecha', 'Fecha',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_fin', $cursos->fecha_fin ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('duracion', 'Duracion del curso en horas: ', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('duracion',$cursos->duracion ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('id_modalidad_curso', 'Modalidad del curso',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::select('id_modalidad_curso', $modalidades_curso, $modalidad_curso, array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('cupos', 'Cantidad de cupos',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('cupos', $cursos->cupos ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('lugar', 'Lugar',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('lugar', $cursos->lugar ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('desc', 'Descripción',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('descripcion', $cursos->descripcion ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('dirigido', 'Dirigido a',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('dirigido_a', $cursos->dirigido_a, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('proposito', 'Propósitos',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('proposito', $cursos->propositos, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('modalidad', 'Modalidad/Estrategia',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('modalidad_estrategias', $cursos->modalidad_estrategias, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('acreditacion', 'Acreditación',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('acreditacion', $cursos->acreditacion, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('perfil', 'Perfil requerido',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('perfil', $cursos->perfil, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('req_tec', 'Requerimientos técnicos',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('requerimientos_tec', $cursos->requerimientos_tec, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('perfil_egresado', 'Perfil del egresado',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('perfil_egresado', $cursos->perfil_egresado, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('instituciones_aval', 'Instituciones que avalan el curso',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('instituciones_aval', $cursos->instituciones_aval, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('aliados', 'Aliados',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('aliados', $cursos->aliados, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('plan_estudio', 'Plan de estudio',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('plan_estudio', $cursos->plan_estudio, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('costo', 'Costo',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('costo', $cursos->costo, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--{!!Form::label('modalidades_pago', 'Modalidades de pago',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                        {{--<div class="col-sm-8">--}}
                            {{--{!! Form::textarea('modalidades_pago', $cursos->modalidades_pago, array('required','class' => 'form-control'))!!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        {!!Form::label('modalidades_pago', 'Modalidades de pago',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            @foreach($modalidad_pago as $modalidad)
                                {!! Form::checkbox('modalidades_pago[]', $modalidad, false) !!} {{$modalidad}} <br>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('activo_carrusel', 'Curso activo en el carrusel?',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::checkbox('activo_carrusel',null, $cursos->activo_carrusel)!!}
                        </div>
                    </div>
                    <div class="form-group" id="imagen_carrusel">
                        {!!Form::label('imagen_carrusel', 'Imagen carrusel',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::file('imagen_carrusel', $cursos->imagen_carrusel, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group" id="descripcion_carrusel">
                        {!!Form::label('desc_carrusel', 'Titulo de la imagen en el carrusel',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('descripcion_carrusel', $cursos->descrpcion_carrusel, array('class' => 'form-control'))!!}
                        </div>
                    </div>

                    {!! Form::submit('Editar', array('class' => 'btn btn-success')) !!}

                    {!! Form::close() !!}
                @endif
            </div>
        @endif
    </div>

@stop
