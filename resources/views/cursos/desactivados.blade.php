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
                <table class="table table-hover">
                    <thead>
                    <tr>
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
                        @foreach($cursos as $curso)
                            @if(!($curso->curso_activo))
                                <tr>
                                    <td>{{ $curso->nombre }}</td>
                                    <td>{{ $curso->tipo_curso  }}</td>
                                    <td>{{ $curso->fecha_inicio  }}</td>
                                    <td>{{ $curso->fecha_fin  }}</td>

                                    <td>
                                        @if(Entrust::can('activar_cursos'))
                                            {{--{!! Form::open(array('method' => 'GET','route' => array('cursos-desactivados/activar/'.$curso->id))) !!}--}}
                                            {{--{!! Form::button('<span class="glyphicon glyphicon-ok" data-toggle="tooltip" data-placement="bottom" title="Activar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-success'))!!}--}}
                                            {{--{!! Form::close() !!}--}}
                                            {!!Form::open(["url"=>"cursos-desactivados/activar/".$curso->id,  "method" => "GET", 'id' => 'form_activar'.$curso->id] )!!}
                                             <button type="button" onclick="activarCurso('{{$curso->id}}')" class="btn btn-success" title="Activar" data-toggle="tooltip" data-placement="bottom" aria-hidden="true">
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
                @if(Entrust::can('ver_lista_cursos'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/cursos" type="button" class="btn btn-success" >Ver cursos activos </a>
                    </div>
                @endif

            </div>
        @endif
    </div>

@stop