@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Cursos
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6 col-sm-12 col-xs-12 ">
                        {!! Form::open(array('method' => 'get', 'route' => array('cursos.buscar'))) !!}
                        <div class="buscador">
                            <select class="form-control " id="param1" name="parametro">
                                <option value="0"  selected="selected"> Buscar por</option>
                                <option value="nombre"  > Nombre</option>
                                <option value="tipo"  > Tipo</option>
                            </select>
                            {!!Form::text('busqueda', null,array( 'placeholder' => 'Escriba su busqueda...','class' => 'form-control', 'id' => 'busq'))!!}
                            {!! Form::select('busqu', $tipos, null, array('class' => 'form-control','hidden' => 'true', 'id' => 'busq2')) !!}
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" ></span> </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Acciones</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        @if($cursos->count())
                            <tbody>
                            @foreach($cursos as $index => $curso)
                                @if($curso->curso_activo)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $curso->nombre }}</td>
                                        <td>{{ $curso->tipo_curso  }}</td>
                                        <td>{{ $curso->inicio->format('d-m-Y') }}</td>
                                        <td>{{ $curso->fin->format('d-m-Y')  }}</td>

                                        <td class="boton_">
                                            @if(Entrust::can('editar_cursos'))
                                                {!! Form::open(array('method' => 'GET','route' => array('cursos.edit', $curso->id))) !!}
                                                    <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Editar" >
                                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                    </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td class="boton_">
                                            @if(Entrust::can('eliminar_cursos'))
                                                {!! Form::open(array('method' => 'DELETE', 'route' => array('cursos.destroy', $curso->id), 'id' => 'form_desactivar'.$curso->id)) !!}
                                                    <button type="button" onclick="desactivarCurso('{{$curso->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                    </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td class="boton_">
                                            @if(Entrust::can('participantes_curso'))
                                                {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/participantes",  "method" => "GET" ])!!}
                                                    <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Participantes">
                                                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                                    </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td class="boton_">
                                            @if(Entrust::can('profesores_curso'))
                                                {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/profesores",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Profesores">
                                                    <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td class="boton_">
                                            @if(Entrust::can('lista_moodle'))
                                                {!!Form::open(["url"=>"cursos/".$curso->id."/secciones",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Lista Moodle">
                                                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        @else
                            @if($busq_)
                                <td></td>
                                <td> 0 resultados de la busqueda</td>
                            @else
                                <td></td>
                                <td>No existen cursos activos</td>
                            @endif
                        @endif
                    </table>
                </div>
                @if(Entrust::can('crear_cursos'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/cursos/create" type="button" class="btn btn-success" >Agregar curso </a>
                    </div>
                @endif
            </div>
        @endif
    </div>

@stop