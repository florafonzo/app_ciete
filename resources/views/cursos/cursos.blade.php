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
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    @if($cursos->count())
                        <tbody>
                        @foreach($cursos as $curso)
                            @if($curso->curso_activo)
                                <tr>
                                    <td>{{ $curso->nombre }}</td>
                                    <td>{{ $curso->tipo_curso  }}</td>
                                    <td>{{ $curso->inicio->format('d-m-Y') }}</td>
                                    <td>{{ $curso->fin->format('d-m-Y')  }}</td>

                                    <td>
                                        @if(Entrust::can('editar_cursos'))
                                            {!! Form::open(array('method' => 'GET','route' => array('cursos.edit', $curso->id))) !!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Editar" >
                                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('eliminar_cursos'))
                                            {!! Form::open(array('method' => 'DELETE', 'route' => array('cursos.destroy', $curso->id), 'id' => 'form_desactivar'.$curso->id)) !!}
                                                {{--{!! Form::button('<span class="glyphicon glyphicon-trash" id="{{$curso->id}}" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#modal_eliminar_cursos','class' => 'btn btn-danger'))!!}--}}
                                                <button type="button" onclick="desactivarCurso('{{$curso->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('participantes_curso'))
                                            {{--{!! Form::open(array('method' => 'GET', 'route' => array('cursos.cursoParticipantes', $curso->id))) !!}--}}
                                            {!!Form::open(["url"=>"cursos/".$curso->id."/participantes",  "method" => "GET" ])!!}
                                            {{--{!! Form::open(array('method' => 'GET', 'action' => 'CursosController@cursoParticipantes', 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}--}}
                                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Participantes">
                                                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('profesores_curso'))
                                            {!!Form::open(["url"=>"cursos/".$curso->id."/profesores",  "method" => "GET" ])!!}
                                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Profesores">
                                                <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @else
                                <?php continue; ?>
                            @endif
                        @endforeach
                        </tbody>
                    @endif
                </table>
                @if(Entrust::can('crear_cursos'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/cursos/create" type="button" class="btn btn-success" >Agregar curso </a>
                    </div>
                @endif
            </div>
        @endif
    </div>

@stop