@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Cursos inscritos
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
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
                            @foreach($cursos as $index => $curso)
                                <tr>
                                    <td>{{ $curso[0]->nombre }}</td>
                                    <td>{{ $tipo_curso[$index] }}</td>
                                    <td>{{ $curso[0]->seccion  }}</td>
                                    <td>{{ $inicio[$index]->format('d-m-Y')  }}</td>
                                    <td>{{ $fin[$index]->format('d-m-Y')  }}</td>
                                    <td>
                                        @if(Entrust::can('ver_notas_part'))
                                            {!!Form::open(["url"=>"participante/cursos/".$curso[0]->id."/notas",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Notas">
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
                </div>
            </div>
        @endif
    </div>



@stop