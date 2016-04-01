@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Cursos desactivados
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('cursos/desactivados.buscar'))) !!}
                        <div class="buscador">
                            <select class="form-control " id="param1" name="parametro">
                                <option value="0"  selected="selected"> Buscar por</option>
                                <option value="nombre"  > Nombre</option>
                                <option value="tipo"  > Tipo</option>
                            </select>
                            {!!Form::text('busqueda', null,array( 'placeholder' => 'Escriba su busqueda...','class' => 'form-control', 'id' => 'busq'))!!}
                            {!! Form::select('busqu', $tipos, null, array('class' => 'form-control','hidden' => 'true', 'id' => 'busq2')) !!}
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
                            <th>Tipo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        @if($cursos->count())
                            <tbody>
                            @foreach($cursos as $index => $curso)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $curso->nombre }}</td>
                                    <td>{{ $curso->tipo_curso  }}</td>
                                    <td>{{ $curso->inicio->format('d-m-Y')  }}</td>
                                    <td>{{ $curso->fin->format('d-m-Y')  }}</td>

                                    <td>
                                        @if(Entrust::can('activar_cursos'))
                                            {!!Form::open(["url"=>"cursos/desactivados/activar/".$curso->id,  "method" => "GET", 'id' => 'form_activar'.$curso->id] )!!}
                                             <button type="button" onclick="activarCurso('{{$curso->id}}')" class="btn btn-success" title="Activar" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
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
                                <td>No existen cursos desactivados</td>
                            @endif
                        @endif
                    </table>
                </div>
                @if(Entrust::can('ver_lista_cursos'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/cursos" type="button" class="btn btn-success" >Ver cursos activos </a>
                    </div>
                @endif

            </div>
        @endif
    </div>

@stop