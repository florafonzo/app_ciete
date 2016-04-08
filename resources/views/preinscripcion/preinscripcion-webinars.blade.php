@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Webinar para preinscripción
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        @if($webinars->count())
                            <tbody>
                            @foreach($webinars as $index => $webinar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $webinar->nombre }}</td>
                                    <td>{{ $webinar->inicio->format('d-m-Y') }}</td>
                                    <td>{{ $webinar->fin->format('d-m-Y')  }}</td>
                                    @if($webinar->activo_preinscripcion)
                                        <td>
                                            @if(Entrust::can('desactivar_preinscripcion'))
                                                {!! Form::open(array('method' => 'GET', 'route' => array('preinscripcion.desactivar-web', $webinar->id), 'id' => 'form_desactivar'.$webinar->id)) !!}
                                                <button type="button" onclick="desactivarPrecurso('{{$webinar->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Desactivar">
                                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    @else
                                        <td>
                                            @if(Entrust::can('activar_preinscripcion'))
                                                {!! Form::open(array('method' => 'GET','route' => array('preinscripcion.activar-web', $webinar->id), 'id' => 'form_activar'.$webinar->id)) !!}
                                                <button type="button" onclick="activarPrecurso('{{$webinar->id}}')" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Activar" >
                                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            {{--@if($busq_)--}}
                                {{--<td></td>--}}
                                {{--<td> 0 resultados de la busqueda</td>--}}
                            {{--@else--}}
                                <td></td>
                                <td>No existen cursos activos</td>
                            {{--@endif--}}
                        @endif
                    </table>
                </div>
            </div>
        @endif
    </div>
@stop