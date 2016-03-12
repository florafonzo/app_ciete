@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Webinars
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
                            @if($webinar->webinar_activo)
                                <tr>
                                    <td>{{ $webinar->nombre }}</td>
                                    <td>{{ $webinar->inicio->format('d-m-Y') }} </td>
                                    <td>{{ $webinar->fin->format('d-m-Y') }} </td>
                                    <td>
                                        @if(Entrust::can('editar_webinars'))
                                            {{--<button><span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span></button>--}}
                                            {!! Form::open(array('method' => 'GET','route' => array('webinars.edit', $webinar->id))) !!}
                                            {!! Form::button('<span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('eliminar_webinars'))
                                            {!! Form::open(array('method' => 'DELETE', 'route' => array('webinars.destroy', $webinar->id), 'id' => 'form_eliminar'.$webinar->id)) !!}                                            
                                                <button type="button" onclick="mostrarModal('{{$webinar->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar" aria-hidden="true">
                                                    <span class="glyphicon glyphicon-trash" ></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('participantes_webinar'))
                                            {!!Form::open(["url"=>"webinars/".$webinar->id."/participantes",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Participantes">
                                                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('profesores_webinar'))
                                            {!!Form::open(["url"=>"webinars/".$webinar->id."/profesores",  "method" => "GET" ])!!}
                                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Profesores">
                                                <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    @endif
                </table>
                @if(Entrust::can('crear_webinars'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/webinars/create" type="button" class="btn btn-success" >Crear Webinar </a>
                    </div>
                @endif
            </div>
        @endif
    </div>



@stop