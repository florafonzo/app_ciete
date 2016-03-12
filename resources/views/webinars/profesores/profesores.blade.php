@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Profesores que dictan {{$webinar->nombre}}
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
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($profesores != null)

                        @foreach($profesores as $profesor)
                            <tr>
                                <td>{{ $profesor[0]->nombre }}</td>
                                <td>{{ $profesor[0]->apellido  }}</td>
                                <td>
                                    @if(Entrust::can('eliminar_prof_webinar'))
                                        {!!Form::open(["url"=>"webinars/".$webinar->id."/profesores/".$profesor[0]->id."/eliminar",  "method" => "delete", "id" => "eliminar_prof_web".$profesor[0]->id ])!!}
                                        <button type="button" onclick="eliminarProfW('{{$profesor[0]->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
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
                                <strong> No existen profesores que dicten este webinar </strong>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('ver_webinars'))
                        <a href="{{URL::to('/')}}/webinars" type="button" class="btn btn-default" style="text-decoration: none"><span class="glyphicon glyphicon-remove"></span> Cancelar </a>
                    @endif
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('agregar_prof_webinar'))
                        {!!Form::open(["url"=>"webinars/".$webinar->id."/profesores/agregar",  "method" => "GET" ])!!}
                        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Agregar profesor al webinar" >
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar
                        </button>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop