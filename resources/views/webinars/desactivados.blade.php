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
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($webinars->count())
                        <tbody>
                        @foreach($webinars as $webinar)
                            @if(!($webinar->webinar_activo))
                                <tr>
                                    <td>{{ $webinar->nombre }}</td>
                                    <td>{{ $webinar->inicio->format('d-m-Y')  }}</td>
                                    <td>{{ $webinar->fin->format('d-m-Y')  }}</td>

                                    <td>
                                        @if(Entrust::can('activar_cursos'))
                                            {!!Form::open(["url"=>"webinars-desactivados/activar/".$webinar->id,  "method" => "GET", 'id' => 'webinar_activar'.$webinar->id] )!!}
                                            <button type="button" onclick="activarWebinar('{{$webinar->id}}')" class="btn btn-success" title="Activar" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
                                                <span class="glyphicon glyphicon-ok"></span>
                                            </button>
                                            {!!Form::close()!!}
                                        @endif
                                    </td>
                                </tr>
                            @else
                                <?php continue; ?>
                            @endif
                        @endforeach
                        </tbody>
                    @endif
                </table>
                @if(Entrust::can('ver_webinars'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/webinars" type="button" class="btn btn-success" >Ver webinars activos </a>
                    </div>
                @endif

            </div>
        @endif
    </div>

@stop