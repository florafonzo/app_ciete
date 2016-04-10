@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Agregar profesores para que dicten el curso {{$curso->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                {{--Seleccione los profesores que desee que dicten el curso:--}}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/profesores/agregar/buscar",  "method" => "GET" ])!!}
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
                            <th>Documento de identidad</th>
                            <th>Agregar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($profesores != null)
                            @foreach($profesores as $index => $profesor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $profesor->nombre }}</td>
                                    <td>{{ $profesor->apellido  }}</td>
                                    <td>{{ $profesor->documento_identidad }}</td>

                                    <td class="">
                                        @if(Entrust::can('agregar_prof_curso'))
                                            {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/profesores/".$profesor->id."/agregar",  "method" => "GET", 'id' => 'prof_agregar'.$profesor->id] )!!}
                                            <button type="button" onclick="agregarProf('{{$profesor->id}}')" class="btn btn-info" title="Agregar profesor al curso" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
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
                                <td> <strong> No existen profesores para agregar </strong></td>
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('profesores_curso'))
                        <a href="{{URL::to('/')}}/cursos/{{$curso->id}}/secciones/{{$seccion}}/profesores" type="button" class="btn btn-default" style="text-decoration: none"> <span class="glyphicon glyphicon-chevron-left"></span> Volver </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop