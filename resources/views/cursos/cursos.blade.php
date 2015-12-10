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
                                {{--<a style="text-decoration:none;" href="{{URL::to('/usuarios')}}"> Usuarios </a>--}}
                            {{--</li>--}}
                            {{--<li class="active menu_usuarios">--}}
                                {{--<a style="text-decoration:none;" href="{{URL::to('/cursos')}}"> Lista de cursos </a>--}}
                            {{--</li>--}}
                            {{--<li class="menu_usuarios">--}}
                                {{--<a href="#"> Carrusel </a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-8 col-sm-8 opciones_part2">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($cursos->count())
                        <tbody>
                        @foreach($cursos as $curso)
                            <tr>
                                <td>{{ $curso->nombre }}</td>
                                <td>{{ $curso->fecha  }}</td>
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
                        @endforeach
                        </tbody>
                    @endif
                </table>
                @if(Entrust::can('crear_cursos'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/cursos/create" type="button" class="btn btn-success" >Agregar curso </a>
                    </div>
                @endif

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
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button id="eliminar_curso" type="button" class="btn btn-danger">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fin Modal de Eliminar--}}

            </div>
        @endif
    </div>



@stop