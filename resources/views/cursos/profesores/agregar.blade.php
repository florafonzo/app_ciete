@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Agregar profesores al curso {{$curso->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                Seleccione los profesores que desee que dicten el curso:
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
                    @if($profesores != null)
                        @foreach($profesores as $profesor)
                            <tr>
                                <td>{{ $profesor->nombre }}</td>
                                <td>{{ $profesor->apellido  }}</td>
                                <td>{{ $profesor->documento_identidad }}</td>

                                <td class="">
                                    @if(Entrust::can('agregar_part_curso'))
                                        {!!Form::open(["url"=>"cursos/".$curso->id."/profesores/".$profesor->id."/agregar",  "method" => "GET", 'id' => 'prof_agregar'.$profesor->id] )!!}
                                        <button type="button" onclick="agregarPart('{{$profesor->id}}')" class="btn btn-info" title="Agregar profesor al curso" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
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
                                <strong> No existen profesores para agregar </strong>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('profesores_curso'))
                        <a href="{{URL::to('/')}}/cursos/{{$curso->id}}/profesores" type="button" class="btn btn-default" style="text-decoration: none"> <span class="glyphicon glyphicon-chevron-left"></span> Volver </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop