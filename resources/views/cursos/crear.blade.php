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
                {!! Form::open(array('method' => 'POST', 'action' => 'CursosController@store', 'files' => true,'class' => 'form-horizontal col-md-10')) !!}
                @include('partials.mensajes')
                <div class="form-group">
                    {!!Form::label('nombre', 'Nombre:', array( 'class' => 'col-md-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('nombre', Session::get('nombre') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('id_tipo', 'Tipo:',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!! Form::select('id_tipo', $tipos, null, array('required','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('fecha_inicio', 'Fecha inicio:',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::input('date', 'fecha_inicio', Session::get('fecha_inicio') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('fecha_fin', 'Fecha fin:',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::input('date', 'fecha_fin', Session::get('fecha_fin') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('duracion', 'Duracion del curso en horas: ', array( 'class' => 'col-md-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('duracion',Session::get('duracion') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('id_modalidad_curso', 'Modalidad del curso:',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!! Form::select('id_modalidad_curso', $modalidad_curso, null, array('required','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('secciones', 'Cantidad de secciones:',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('secciones', Session::get('secciones') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('mini', 'Cantidad de cupos MIN:',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::text('mini', Session::get('min') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('Cantidad de cupos MAX:')!!}
                    {{--<div class="col-sm-8">--}}
                        {!!Form::text('maxi', Session::get('max') ,array('required','class' => 'form-control'))!!}
                    {{--</div>--}}
                </div>
                <div class="form-group">
                    {!!Form::label('Lugar:')!!}
                    {{--<div class="col-sm-8">--}}
                        {!!Form::text('lugar', Session::get('lugar') ,array('required','class' => 'form-control'))!!}
                    {{--</div>--}}
                </div>
                <div class="form-group" style="padding-left: 15px;">
                    {!!Form::label('Descripción:')!!}
                    {!!Form::textarea('descripcion', Session::get('descripcion') ,array('required','class' => 'form-control ckeditor'))!!}
                    {{--<div class="col-sm-8">--}}

                    {{--</div>--}}
                </div>
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('dirigido', 'Dirigido a',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('dirigido_a', Session::get('dirigido_a'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('proposito', 'Propósitos',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('proposito', Session::get('proposito'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('modalidad', 'Modalidad/Estrategia',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('modalidad_estrategias', Session::get('modalidad_estrategias'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('acreditacion', 'Acreditación',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('acreditacion',Session::get('acreditacion'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('perfil', 'Perfil requerido',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('perfil', Session::get('perfil'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('req_tec', 'Requerimientos técnicos',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('requerimientos_tec', Session::get('requerimientos_tec'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('perfil_egresado', 'Perfil del egresado',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('perfil_egresado', Session::get('perfil_egresado'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('instituciones_aval', 'Instituciones que avalan el curso',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('instituciones_aval', Session::get('instituciones_aval'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('aliados', 'Aliados',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('aliados', Session::get('aliados'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--{!!Form::label('plan_estudio', 'Plan de estudio',  array( 'class' => 'col-md-4 control-label'))!!}--}}
                    {{--<div class="col-sm-8">--}}
                        {{--{!! Form::textarea('plan_estudio', Session::get('plan_estudio'), array('required','class' => 'form-control'))!!}--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">
                    {!!Form::label('costo', 'Costo',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!! Form::text('costo', Session::get('costo'), array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('modalidades_pago', 'Modalidades de pago',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        @foreach($modalidad_pago as $modalidad)
                           {!! Form::checkbox('modalidades_pago[]', $modalidad, false) !!} {{$modalidad}} <br>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('activo_carrusel','Curso activo en el carrusel?')!!}
                    {{--<div class="col-sm-8">--}}
                        {!! Form::checkbox('activo_carrusel',null, false)!!}
                    {{--</div>--}}
                </div>
                <div class="form-group" id="imagen_carrusel">
                    {!!Form::label('imagen_carrusel', 'Imagen carrusel',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!! Form::file('imagen_carrusel', null, array('class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group" id="descripcion_carrusel">
                    {!!Form::label('desc_carrusel', 'Titulo de la imagen en el carrusel',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!! Form::text('descripcion_carrusel', Session::get('descripcion_carrusel'), array('class' => 'form-control'))!!}
                    </div>
                </div>
                <a href="{{URL::to("/")}}/cursos" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Crear </button>

                {!! Form::close() !!}
            </div>
        @endif
    </div>

@stop
