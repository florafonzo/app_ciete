@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Webinars desactivados
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('webinars/desactivados.buscar'))) !!}
                        <div class="buscador">
                            <select class="form-control " name="parametro">
                                <option value="0"  selected="selected"> Buscar por</option>
                                <option value="nombre"  > Nombre</option>
                            </select>
                            {!!Form::text('busqueda', null,array( 'placeholder' => 'Escriba su busqueda...','class' => 'form-control'))!!}
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
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        @if($webinars->count())
                            <tbody>
                            @foreach($webinars as $index => $webinar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $webinar->nombre }}</td>
                                    <td>{{ $webinar->inicio->format('d-m-Y')  }}</td>
                                    <td>{{ $webinar->fin->format('d-m-Y')  }}</td>

                                    <td>
                                        @if(Entrust::can('activar_cursos'))
                                            {!!Form::open(["url"=>"webinars/desactivados/activar/".$webinar->id,  "method" => "GET", 'id' => 'webinar_activar'.$webinar->id] )!!}
                                            <button type="button" onclick="activarWebinar('{{$webinar->id}}')" class="btn btn-success" title="Activar" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
                                                <span class="glyphicon glyphicon-ok"></span>
                                            </button>
                                            {!!Form::close()!!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            @if($busq_)
                                <td></td>
                                <td> 0 resultados de la busqueda</td>
                            @else
                                <td></td>
                                <td>No existen cursos activos</td>
                            @endif
                        @endif
                    </table>
                </div>
                @if(Entrust::can('ver_webinars'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/webinars" type="button" class="btn btn-success" >Ver webinars activos </a>
                    </div>
                @endif

            </div>
        @endif
    </div>

@stop