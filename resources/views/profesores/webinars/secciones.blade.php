@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Secciones del webinar {{$webinar->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Sección</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($secciones != null)
                        <tbody>
                        @foreach($secciones as $seccion)
                            <tr>
                                <td>{{ $seccion }}</td>
                                <td>
                                    @if(Entrust::can('listar_alumnos'))
                                        {!!Form::open(["url"=>"profesor/webinars/".$webinar->id."/secciones/".$seccion."/participantes",  "method" => "GET" ])!!}
                                        <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Participantes">
                                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                        </button>
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
                @if(Entrust::can('ver_cursos_profe'))
                    <a href="{{URL::to("/")}}/profesor/webinars" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Volver</a>
                @endif
            </div>
        @endif
    </div>



@stop