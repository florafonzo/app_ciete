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
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('webinars.buscar'))) !!}
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
                            <th></th>
                            <th></th>

                        </tr>
                        </thead>
                        @if($webinars->count())
                            <tbody>
                            @foreach($webinars as $index => $webinar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $webinar->nombre }}</td>
                                    <td>{{ $webinar->inicio->format('d-m-Y') }} </td>
                                    <td>{{ $webinar->fin->format('d-m-Y') }} </td>
                                    <td class="boton_">
                                        @if(Entrust::can('editar_webinars'))
                                            {{--<button><span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span></button>--}}
                                            {!! Form::open(array('method' => 'GET','route' => array('webinars.edit', $webinar->id))) !!}
                                                <button type="button" class='btn btn-info' data-toggle='tooltip' data-placement="bottom" title="Editar" aria-hidden="true">
                                                    <span class="glyphicon glyphicon-pencil" ></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td class="boton_">
                                        @if(Entrust::can('eliminar_webinars'))
                                            {!! Form::open(array('method' => 'DELETE', 'route' => array('webinars.destroy', $webinar->id), 'id' => 'form_eliminar'.$webinar->id)) !!}
                                                <button type="button" onclick="mostrarModal('{{$webinar->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar" aria-hidden="true">
                                                    <span class="glyphicon glyphicon-trash" ></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td class="boton_">
                                        @if(Entrust::can('participantes_webinar'))
                                            {!!Form::open(["url"=>"webinars/".$webinar->id."/secciones/participantes",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Participantes">
                                                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td class="boton_">
                                        @if(Entrust::can('profesores_webinar'))
                                            {!!Form::open(["url"=>"webinars/".$webinar->id."/secciones/profesores",  "method" => "GET" ])!!}
                                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Profesores">
                                                <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
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
                @if(Entrust::can('crear_webinars'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/webinars/create" type="button" class="btn btn-success" >Crear Webinar </a>
                    </div>
                @endif
            </div>
        @endif
    </div>



@stop