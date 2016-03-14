@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Participantes del curso {{$curso->nombre}}
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
                        <th>Apellido</th>
                        {{--<th>Fecha Inicio</th>--}}
                        {{--<th>Fecha Fin</th>--}}
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($participantes != null)

                            @foreach($participantes as $participante)
                                <tr>
                                    <td>{{ $participante[0]->nombre }}</td>
                                    <td>{{ $participante[0]->apellido  }}</td>
                                    {{--<td>{{ $participantes->fecha_inicio  }}</td>--}}
                                    {{--<td>{{ $participantes->fecha_fin  }}</td>--}}

                                    <td>
                                        @if(Entrust::can('eliminar_part_curso'))
                                            {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/participantes/".$participante[0]->id."/eliminar",  "method" => "delete", "id" => "form_eliminar_part".$participante[0]->id ])!!}
                                            <button type="button" onclick="eliminarPart('{{$participante[0]->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
                                    <strong> No existen participantes inscritos en este curso </strong>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('ver_lista_cursos'))
                        <a href="{{URL::to('/')}}/cursos" type="button" class="btn btn-default" style="text-decoration: none"><span class="glyphicon glyphicon-remove"></span> Cancelar </a>
                    @endif
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('agregar_part_curso'))
                        {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/participantes/agregar",  "method" => "GET" ])!!}
                        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Agregar participante al curso" >
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar
                        </button>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop