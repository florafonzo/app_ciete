@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Agregar participantes al curso {{$curso->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                {{--Seleccione los participantes que desee agregar al curso:--}}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/participantes/agregar/buscar",  "method" => "GET" ])!!}
                        {{--{!! Form::open(array('method' => 'get', 'route' => array('usuarios.buscar'), 'id' => 'form_busq')) !!}--}}
                        <div class="buscador">
                            <select class="form-control " name="parametro">
                                <option value="0"  selected="selected">Buscar por</option>
                                <option value="nombre">Nombre</option>
                                <option value="apellido">Apellido</option>
                                <option value="documento_identidad">Dcocumento de identidad</option>
                            </select>
                            {!!Form::text('busqueda', null,array('placeholder' => 'Escriba su busqueda...','class' => 'form-control bus'))!!}
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
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Agregar</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($participantes != null)
                                @foreach($participantes as $index => $partici)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $partici->nombre }}</td>
                                        <td>{{ $partici->apellido  }}</td>
                                        <td>{{ $partici->documento_identidad }}</td>

                                        <td class="">
                                            @if(Entrust::can('agregar_part_curso'))
                                                {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/participantes/".$partici->id."/agregar",  "method" => "GET", 'id' => 'part_agregar'.$partici->id] )!!}
                                                    <button type="button" onclick="agregarPart('{{$partici->id}}')" class="btn btn-info" title="Agregar participante al curso" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                {!!Form::close()!!}
                                                {{--{!! Form::checkbox('agregar[]',null, null)!!}--}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @if($busq_)
                                    <td></td>
                                    <td><strong> 0 resultados de la busqueda </strong></td>
                                @else
                                    <td></td>
                                    <td> <strong> No existen participantes para agregar </strong></td>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('participantes_curso'))
                        <a href="{{URL::to('/')}}/cursos/{{$curso->id}}/secciones/{{$seccion}}/participantes" type="button" class="btn btn-default" style="text-decoration: none"> <span class="glyphicon glyphicon-chevron-left"></span> Volver </a>
                    @endif
                </div>
                {{--<div class="col-md-2" style="">--}}
                    {{--@if(Entrust::can('agregar_part_curso'))--}}
                        {{--{!!Form::open(["url"=>"cursos/".$curso->id."/participantes/agregar",  "method" => "POST" ])!!}--}}
                        {{--<button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Agregar participante al curso" >--}}
                            {{--<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar--}}
                        {{--</button>--}}
                        {{--{!! Form::close() !!}--}}
                    {{--@endif--}}
                {{--</div>--}}
            </div>
        @endif
    </div>

@stop