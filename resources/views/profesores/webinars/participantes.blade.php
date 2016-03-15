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
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Documento de identidad</th>
                        {{--<th>Acciones</th>--}}
                    </tr>
                    </thead>
                    @if($participantes != null)
                        <tbody>
                        @foreach($participantes as $participante)
                            <tr>
                                <td>{{ $participante[0]->nombre }}</td>
                                <td>{{ $participante[0]->apellido }}</td>
                                <td>{{ $participante[0]->documento_identidad }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    @else
                        <td>No existen participantes</td>
                    @endif
                </table>
                @if(Entrust::can('ver_perfil_prof'))
                    <a href="{{URL::to("/")}}/profesor/webinars/{{$webinar->id}}/secciones" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Volver</a>
                @endif
            </div>
        @endif
    </div>



@stop