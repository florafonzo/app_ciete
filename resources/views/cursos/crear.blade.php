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
                {{--@if($tipos->count())--}}
                    {!! Form::open(array('method' => 'POST', 'action' => 'CursosController@store', 'files' => true,'class' => 'form-horizontal col-md-10')) !!}

                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', null ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">

                        {!!Form::label('id_tipo', 'Tipo',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::select('id_tipo', $tipos, null, array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fecha_inicio', 'Fecha',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_inicio', null ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fecha_fin', 'Fecha',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_fin', null ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('lugar', 'Lugar',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('lugar', null ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('desc', 'Descripción',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('descripcion', null ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('dirigido', 'Dirigido a',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('dirigido_a', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('propositos', 'Propósitos',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('propositos', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('modalidad', 'Modalidad/Estrategia',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('modalidad_estrategias', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('acreditacion', 'Acreditación',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('acreditacion',null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('perfil', 'Perfil requerido',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('perfil', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('req_tec', 'Requerimientos técnicos',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('requerimientos_tec', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('perfil_egresado', 'Perfil del egresado',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('perfil_egresado', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('instituciones_aval', 'Instituciones que avalan el curso',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('instituciones_aval', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('aliados', 'Aliados',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('aliados', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('plan_estudio', 'Plan de estudio',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('plan_estudio', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('costo', 'Costo',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('costo', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('modalidades_pago', 'Modalidades de pago',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('modalidades_pago', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('imagen_carrusel', 'Imagen carrusel',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::file('imagen_carrusel', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('desc_carrusel', 'Titulo de la imagen en el carrusel',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('descripcion_carrusel', null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('activo_carrusel', 'Curso activo en el carrusel?',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::checkbox('activo_carrusel',null, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>

                    {!! Form::submit('Editar', array('class' => 'btn btn-success')) !!}

                    {!! Form::close() !!}
                {{--@endif--}}
            </div>
        @endif
    </div>

@stop
