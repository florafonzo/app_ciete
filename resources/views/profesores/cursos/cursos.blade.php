@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Cursos que dicta
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('profesor.cursos.buscar'))) !!}
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
                            <th>Seccion</th>
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        @if($cursos != null)
                            <tbody>
                            @if($busq)
                                @foreach($cursos as $index => $curso)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $curso->nombre }}</td>
                                        <td>{{ $tipo_curso[$index] }}</td>
                                        <td>{{ $curso->seccion  }}</td>
                                        <td>{{ $inicio[$index]->format('d-m-Y')  }}</td>
                                        <td>{{ $fin[$index]->format('d-m-Y')  }}</td>
                                        <td>
                                            @if(Entrust::can('ver_notas_profe'))
                                                {!!Form::open(["url"=>"profesor/cursos/".$curso->id."/secciones",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Secciones">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($cursos as $index => $curso)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $curso[0]->nombre }}</td>
                                        <td>{{ $tipo_curso[$index] }}</td>
                                        <td>{{ $curso[0]->seccion  }}</td>
                                        <td>{{ $inicio[$index]->format('d-m-Y')  }}</td>
                                        <td>{{ $fin[$index]->format('d-m-Y')  }}</td>
                                        <td>
                                            @if(Entrust::can('ver_notas_profe'))
                                                {!!Form::open(["url"=>"profesor/cursos/".$curso[0]->id."/secciones",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Secciones">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
                    <a href="{{URL::to("/")}}" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                @endif
            </div>
        @endif
    </div>



@stop