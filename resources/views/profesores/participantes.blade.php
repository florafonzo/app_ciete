@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Participantes del curso {{$curso->nombre}} <br/> Sección - {{$seccion}}
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
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($participantes != null)
                        <tbody>
                        @foreach($participantes as $participante)
                            <tr>
                                <td>{{ $participante->nombre }}</td>
                                <td>{{ $participante->apellido }}</td>
                                <td>{{ $participante->documento_identidad }}</td>
                                <td>
                                    @if(Entrust::can('ver_notas_profe'))
                                        {!!Form::open(["url"=>"profesor/cursos/".$curso->id."/secciones/".$seccion."/participantes/".$participante->id."/notas",  "method" => "GET" ])!!}
                                        <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Notas">
                                            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                        </button>
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @else
                        <td>No existen participantes</td>
                    @endif
                </table>
                @if(Entrust::can('ver_perfil_prof'))
                    <a href="{{URL::to("/")}}/profesor/cursos/{{$curso}}/secciones" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Volver</a>
                @endif
            </div>
        @endif
    </div>



@stop