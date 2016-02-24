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
                    @if($participantes != null)
                        <tbody>
                        @foreach($participantes as $participante)
                            <tr>
                                <td>{{ $participante[0]->nombre }}</td>
                                <td>{{ $participante[0]->apellido  }}</td>
                                <td>{{ $participante[0]->documento_identidad }}</td>

                                <td class="">
                                    @if(Entrust::can('agregar_part_curso'))
                                        {!! Form::checkbox('agregar[]',null, false)!!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('participantes_curso'))
                        <a href="{{URL::to('/')}}/cursos/{{$curso->id}}/participantes" type="button" class="btn btn-default" style="text-decoration: none"> <span class="glyphicon glyphicon-remove"></span> Cancelar </a>
                    @endif
                </div>
                <div class="col-md-2" style="">
                    @if(Entrust::can('agregar_part_curso'))
                        {!!Form::open(["url"=>"cursos/".$curso->id."/participantes/agregar",  "method" => "POST" ])!!}
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