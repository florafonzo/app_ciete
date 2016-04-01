@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Notas - {{$curso[0]->nombre}}
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
                            <th>Evaluación</th>
                            <th>Valor</th>
                            <th>Nota</th>
                        </tr>
                        <tbody>
                        @if($notas->count())
                            @foreach($notas as $index => $nota)
                                <tr>
                                    <td>{{ $nota->evaluacion  }}</td>
                                    <td>{{ $nota->porcentaje  }}%</td>
                                    <td>{{ $nota->nota  }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td style="font-weight: bold">Nota Final</td>
                                <td></td>
                                <td>{{$promedio}}</td>
                            </tr>
                            <tr>
                                 <td>Queda por evaluar {{$porcentaje}}%</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @else
                            <td>No posee calificaciones por los momentos</td>
                        @endif
                        </tbody>
                    </table>
                </div>
                @if(Entrust::can('ver_cursos_part'))
                    <div style="text-align: center;">
                        <a href="{{URL::to("/")}}/participante/cursos" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                    </div>
                @endif
            </div>
        @endif
    </div>
@stop