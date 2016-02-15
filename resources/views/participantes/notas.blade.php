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
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Evaluación</th>
                        <th>Nota</th>
                        <th></th>
                    </tr>
                    <tbody>
                    @if($notas->count())

                        @foreach($notas as $index => $nota)
                            <tr>
                                <td>{{ $nota->evaluacion  }}</td>
                                <td>{{ $nota->nota  }}</td>
                            </tr>
                        @endforeach

                    @else
                        <t>No posee calificaciones por los momentos</t>
                    @endif
                    </tbody>
                </table>
                <div class="" style="text-align: center;">
                    <a href="{{URL::to("/")}}/participante/cursos" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                </div>
            </div>
        @endif
    </div>
@stop