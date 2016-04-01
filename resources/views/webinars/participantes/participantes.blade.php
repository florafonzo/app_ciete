@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Participantes del webinar {{$webinar->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!!Form::open(["url"=>"webinars/".$webinar->id."/secciones/".$seccion."/participantes/buscar",  "method" => "GET" ])!!}
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
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($participantes != null)
                                @foreach($participantes as $index => $participante)
                                    @if($busq)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $participante->nombre }}</td>
                                            <td>{{ $participante->apellido  }}</td>
                                            <td>{{ $participante->documento_identidad  }}</td>
                                            <td>
                                                @if(Entrust::can('eliminar_part_webinar'))
                                                    {!!Form::open(["url"=>"webinars/".$webinar->id."/participantes/".$participante->id."/eliminar",  "method" => "delete", "id" => "eliminar_part_web".$participante->id ])!!}
                                                    <button type="button" onclick="eliminarPartW('{{$participante->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $participante[0]->nombre }}</td>
                                            <td>{{ $participante[0]->apellido  }}</td>
                                            <td>{{ $participante[0]->documento_identidad  }}</td>
                                            <td>
                                                @if(Entrust::can('eliminar_part_webinar'))
                                                    {!!Form::open(["url"=>"webinars/".$webinar->id."/secciones/".$seccion."participantes/".$participante[0]->id."/eliminar",  "method" => "delete", "id" => "eliminar_part_web".$participante[0]->id ])!!}
                                                    <button type="button" onclick="eliminarPartW('{{$participante[0]->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                @if($busq_)
                                    <td></td>
                                    <td><strong> 0 resultados de la busqueda </strong></td>
                                @else
                                    <td></td>
                                    <td> <strong> No existen cursos activos </strong></td>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('ver_webinars'))
                        <a href="{{URL::to('/')}}/webinars/{{$webinar->id}}/secciones/participantes" type="button" class="btn btn-default" style="text-decoration: none"><span class="glyphicon glyphicon-remove"></span> Cancelar </a>
                    @endif
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('agregar_part_webinar'))
                        {!!Form::open(["url"=>"webinars/".$webinar->id."/secciones/".$seccion."/participantes/agregar",  "method" => "GET" ])!!}
                        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Agregar participante al webinar" >
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar
                        </button>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop