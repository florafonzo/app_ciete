@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Profesores que dictan {{$curso->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/profesores/buscar",  "method" => "GET" ])!!}
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
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($profesores != null)
                            @foreach($profesores as $index => $profesor)
                                @if($busq_)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $profesor->nombre }}</td>
                                        <td>{{ $profesor->apellido  }}</td>
                                        <td>{{ $profesor->documento_identidad }}</td>
                                        <td>
                                            @if(Entrust::can('eliminar_prof_curso'))
                                                {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/profesores/".$profesor->id."/eliminar",  "method" => "delete", "id" => "form_eliminar_prof".$profesor->id ])!!}
                                                <button type="button" onclick="eliminarProf('{{$profesor->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $profesor[0]->nombre }}</td>
                                        <td>{{ $profesor[0]->apellido  }}</td>
                                        <td>{{ $profesor[0]->documento_identidad }}</td>
                                        <td>
                                            @if(Entrust::can('eliminar_prof_curso'))
                                                {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/profesores/".$profesor[0]->id."/eliminar",  "method" => "delete", "id" => "form_eliminar_prof".$profesor[0]->id ])!!}
                                                <button type="button" onclick="eliminarProf('{{$profesor[0]->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            @if($busq_)
                                <td></td>
                                <td><strong> 0 resultados de la busqueda </strong></td>
                            @else
                                <td></td>
                                <td> <strong> No existen profesores que dicten el curso </strong></td>
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('ver_lista_cursos'))
                        <a href="{{URL::to('/')}}/cursos" type="button" class="btn btn-default" style="text-decoration: none"><span class="glyphicon glyphicon-remove"></span> Cancelar </a>
                    @endif
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('agregar_prof_curso'))
                        {!!Form::open(["url"=>"cursos/".$curso->id."/secciones/".$seccion."/profesores/agregar",  "method" => "GET" ])!!}
                        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Agregar profesor al curso" >
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar
                        </button>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop