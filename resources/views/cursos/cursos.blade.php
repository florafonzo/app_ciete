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
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($cursos->count())
                        <tbody>
                        @foreach($cursos as $curso)
                            @if($curso->curso_activo)
                                <tr>
                                    <td>{{ $curso->nombre }}</td>
                                    <td>{{ $curso->tipo_curso  }}</td>
                                    <td>{{ $curso->fecha_inicio  }}</td>
                                    <td>{{ $curso->fecha_fin  }}</td>

                                    <td>
                                        @if(Entrust::can('editar_cursos'))
                                            {!! Form::open(array('method' => 'GET','route' => array('cursos.edit', $curso->id))) !!}
                                            {!! Form::button('<span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('eliminar_cursos'))
                                            {!! Form::open(array('method' => 'DELETE', 'route' => array('cursos.destroy', $curso->id), 'id' => 'form_eliminar_cursos')) !!}
                                                {!! Form::button('<span class="glyphicon glyphicon-trash" id="{{$curso->id}}" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#modal_eliminar_cursos','class' => 'btn btn-danger'))!!}
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
                @if($cursos->count())
                    {{-- Fin Modal de Eliminar--}}
                    <div class="modal fade" id="modal_eliminar_cursos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Eliminación de curso</h4>
                                </div>
                                <div class="modal-body">
                                    <h5>¿ Está usted seguro de que desea eliminar este curso ?</h5>
                                </div>
                                <div class="modal-footer">
                                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>--}}

                                    {!! Form::open(array('method' => 'DELETE', 'route' => array('cursos.destroy', $curso->id), 'id' => 'form_eliminar_cursos')) !!}
                                        {!! Form::button('Cancelar', array('type' => 'button','data-dismiss' => 'modal','class' => 'btn btn-default'))!!}
                                        {!! Form::button('Eliminar', array('type' => 'submit','class' => 'btn btn-danger'))!!}
                                    {!! Form::close() !!}

                                    {{--<button id="eliminar_curso" type="button" class="btn btn-danger">Eliminar</button>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin Modal de Eliminar--}}
                @endif

            </div>
        @endif
    </div>

@stop