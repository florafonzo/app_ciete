@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Edición de Curso
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                @if($cursos->count())
                    {!! Form::open(array('method' => 'PUT', 'route' => array('cursos.update', $cursos->id),'files' => true, 'class' => 'form-horizontal col-md-12')) !!}

                    <div class="form-group">
                        {!!Form::label('nombre_l', 'Nombre:', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', $cursos->nombre ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('id_tipo_l', 'Tipo:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::select('id_tipo', $tipos, $tipo, array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fechaI_l', 'Fecha inicio:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_inicio', $inicio->format('d/m/Y') ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('fechaF_l', 'Fecha fin:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::input('date', 'fecha_fin', $fin->format('d/m/Y') ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('duracion_l', 'Duracion del curso en horas: ', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('duracion',$cursos->duracion ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('id_modalidad_curso_l', 'Modalidad del curso:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::select('id_modalidad_curso', $modalidades_curso, $modalidad_curso, array('required','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('secciones_l', 'Cantidad de secciones:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('secciones', $cursos->secciones ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('mini_l', 'Cantidad de cupos MIN:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('mini', $cursos->min ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('maxi_l', 'Cantidad de cupos MAX:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('maxi', $cursos->max ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('lugar_l', 'Lugar:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::text('lugar', $cursos->lugar ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('desc_l', 'Descripción:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('descripcion', $cursos->descripcion ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('dirigido_l', 'Dirigido a:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('dirigido_a', $cursos->dirigido_a, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('proposito_l', 'Propósitos:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('proposito', $cursos->propositos, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('modalidad_l', 'Modalidad/Estrategia:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('modalidad_estrategias', $cursos->modalidad_estrategias, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('acreditacion_l', 'Acreditación:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('acreditacion', $cursos->acreditacion, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('perfil_l', 'Perfil requerido:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('perfil', $cursos->perfil, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('req_tec_l', 'Requerimientos técnicos:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('requerimientos_tec', $cursos->requerimientos_tec, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('perfil_egresado_l', 'Perfil del egresado:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('perfil_egresado', $cursos->perfil_egresado, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('instituciones_aval_l', 'Instituciones que avalan el curso:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('instituciones_aval', $cursos->instituciones_aval, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('aliados_l', 'Aliados:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('aliados', $cursos->aliados, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('plan_estudio_l', 'Plan de estudio:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::textarea('plan_estudio', $cursos->plan_estudio, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('costo_l', 'Costo:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('costo', $cursos->costo, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('modalidades_pago_l', 'Modalidades de pago:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            @foreach($modalidad_pago as $index => $modalidad)
                                @if($pagos[$index] == true)
                                    {!! Form::checkbox('modalidades_pago[]', $modalidad, true) !!} {{$modalidad}} <br>
                                @else
                                    {!! Form::checkbox('modalidades_pago[]', $modalidad, false) !!} {{$modalidad}} <br>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('activo_carrusel_l', 'Curso activo en el carrusel?:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::checkbox('activo_carrusel',null, $cursos->activo_carrusel)!!}
                        </div>
                    </div>
                    <div class="form-group" id="imagen_carrusel">
                        {!!Form::label('imagen_carrusel_l', 'Imagen carrusel:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::file('imagen_carrusel', $cursos->imagen_carrusel, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group" id="descripcion_carrusel">
                        {!!Form::label('desc_carrusel_l', 'Titulo de la imagen en el carrusel:',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!! Form::text('descripcion_carrusel', $cursos->descrpcion_carrusel, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <a href="{{URL::to("/")}}/cursos" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save" ></span> Guardar </button>

                    {!! Form::close() !!}
                @endif
            </div>
        @endif
    </div>

@stop
