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
                Seleccione los participantes que desee agregar al curso:
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Cédula</th>
                        <th>Agregar</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($participantes != null)
                            @foreach($participantes as $partici)
                                <tr>
                                    <td>{{ $partici->nombre }}</td>
                                    <td>{{ $partici->apellido  }}</td>
                                    <td>{{ $partici->documento_identidad }}</td>

                                    <td class="">
                                        @if(Entrust::can('agregar_part_curso'))
                                            {!!Form::open(["url"=>"cursos/".$curso->id."/participantes/".$partici->id."/agregar",  "method" => "GET", 'id' => 'part_agregar'.$partici->id] )!!}
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
                        <tr>
                            <td>
                                <strong> No existen participantes para agregar </strong>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('participantes_curso'))
                        <a href="{{URL::to('/')}}/cursos/{{$curso->id}}/participantes" type="button" class="btn btn-default" style="text-decoration: none"> <span class="glyphicon glyphicon-chevron-left"></span> Volver </a>
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