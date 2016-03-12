@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Agregar profesores para que dicten el webinar {{$webinar->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                Seleccione los profesores que desee que dicten el webinar:
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
                                    @if(Entrust::can('agregar_prof_webinar'))
                                        {!!Form::open(["url"=>"webinars/".$webinar->id."/profesores/".$profesor->id."/agregar",  "method" => "GET", 'id' => 'prof_agregar_web'.$profesor->id] )!!}
                                        <button type="button" onclick="agregarProfW('{{$profesor->id}}')" class="btn btn-info" title="Agregar profesor al webinar" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
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
                    @if(Entrust::can('profesores_webinar'))
                        <a href="{{URL::to('/')}}/webinars/{{$webinar->id}}/profesores" type="button" class="btn btn-default" style="text-decoration: none"> <span class="glyphicon glyphicon-chevron-left"></span> Volver </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop