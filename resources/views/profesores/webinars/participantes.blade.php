@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Participantes del curso {{$webinar->nombre}} <br/> Sección - {{$seccion}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!!Form::open(["url"=>"profesor/webinars/".$webinar->id."/secciones/".$seccion."/participantes/buscar",  "method" => "GET" ])!!}
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
                            {{--<th>Acciones</th>--}}
                        </tr>
                        </thead>
                        @if($participantes != null)
                            <tbody>
                            @foreach($participantes as $index => $participante)
                                @if($busq)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $participante->nombre }}</td>
                                        <td>{{ $participante->apellido }}</td>
                                        <td>{{ $participante->documento_identidad }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $participante[0]->nombre }}</td>
                                        <td>{{ $participante[0]->apellido }}</td>
                                        <td>{{ $participante[0]->documento_identidad }}</td>
                                    </tr>
                                @endif
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
                @if(Entrust::can('ver_perfil_prof'))
                    <a href="{{URL::to("/")}}/profesor/webinars/{{$webinar->id}}/secciones" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Volver</a>
                @endif
            </div>
        @endif
    </div>



@stop