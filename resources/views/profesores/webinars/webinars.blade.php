@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Webinars que dicta
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
                        <th>Seccion</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($webinars != null)
                        <tbody>
                        @foreach($webinars as $index => $webinar)
                            <tr>
                                <td>{{ $webinar[0]->nombre }}</td>
                                <td>{{ $webinar[0]->seccion  }}</td>
                                <td>{{ $inicio[$index]->format('d-m-Y')  }}</td>
                                <td>{{ $fin[$index]->format('d-m-Y')  }}</td>
                                <td>
                                    @if(Entrust::can('ver_notas_profe'))
                                        {!!Form::open(["url"=>"profesor/webinars/".$webinar[0]->id."/secciones",  "method" => "GET" ])!!}
                                        <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Secciones">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                        </button>
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
                @if(Entrust::can('ver_perfil_prof'))
                    <a href="{{URL::to("/")}}" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                @endif
            </div>
        @endif
    </div>



@stop