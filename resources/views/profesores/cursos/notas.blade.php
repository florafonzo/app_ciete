@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Notas de {{$participante->nombre}} {{$participante->apellido}}<br/>{{$curso->nombre}}
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
                            <th>Acciones</th>
                        </tr>
                        <tbody>
                        @if($notas->count())
                            @foreach($notas as $index => $nota)
                                <tr>
                                    <td>{{ $nota->evaluacion  }}</td>
                                    <td>{{ $nota->porcentaje  }}%</td>
                                    <td>{{ $nota->nota  }}</td>
                                    <td>
                                        @if(Entrust::can('editar_notas'))
                                            <button type="button" data-toggle="modal" data-id="{{$nota->id}}" data-curso="{{$curso->id}}" data-seccion="{{$seccion}}" data-part="{{$participante->id}}" class='btn btn-info edit_nota' data-toggle='tooltip' data-placement="bottom" title="Editar" aria-hidden="true">
                                                <span class="glyphicon glyphicon-pencil" ></span>
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if(Entrust::can('eliminar_notas'))
                                            {!!Form::open(["url"=>"profesor/cursos/".$curso->id."/secciones/".$seccion."/participantes/".$participante->id."/notas/".$nota->id,  "method" => "DELETE", "id" => "form_eliminar".$nota->id ])!!}
                                            <button type="button" onclick="mostrarModal('{{$nota->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar" aria-hidden="true">
                                                <span class="glyphicon glyphicon-trash" ></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td style="font-weight: bold">Nota Final</td>
                                <td></td>
                                <td><strong>{{$promedio}}</strong></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Queda por evaluar {{$porcentaje}}%</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @else
                            <td>El participante no posee calificaciones por los momentos</td>
                        @endif
                        </tbody>
                    </table>
                </div>
                @if(Entrust::can('ver_notas_profe'))
                    <a href="{{URL::to("/")}}/profesor/cursos/{{$curso->id}}/secciones/{{$seccion}}/participantes" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                @endif
                @if(Entrust::can('agregar_notas'))
                    <button data-toggle="modal" data-target="#notasModal" class="btn btn-success text-right pull-right" id="agregar_nota"><span class="glyphicon glyphicon-plus"></span> Agregar nota</button>
                @endif
            </div>
        @endif
    </div>

    {{--Modal para agregar notas--}}
    <div class="modal fade" id="notasModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{--<a href="{{URL::to('/')}}/profesor/cursos/{{$curso->id}}/secciones/{{$seccion}}/participantes/{{$participante->id}}/notas" class="pull-right"> <span class="glyphicon glyphicon-remove" style="color: #333;"></span> </a>--}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4> Calificar </h4>
                </div>
                <div class="modal-body">
                    {!!Form::open(['url' => 'profesor/cursos/'.$curso->id.'/secciones/'.$seccion.'/participantes/'.$participante->id.'/notas',  "method" => "post", "id" => "form_notan"])!!}
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 col-xs-12">
                            <div class="form-group">
                                {!!Form::label('nombre', 'Evaluación:', array( 'class' => 'col-md-4 izq')) !!}
                                {{--<div class="col-sm-8">--}}
                                    {!!Form::text('evaluacion', Session::get('evaluacion') ,array('required', 'class' => 'form-control')) !!}
                                {{--</div>--}}
                            </div>
                            <div class="form-group">
                                {!!Form::label('nota', 'Nota:', array( 'class' => 'col-md-4 izq')) !!}
                                {{--<div class="col-sm-8">--}}
                                    {!!Form::text('nota', Session::get('nota') ,array('required', 'class' => 'form-control')) !!}
                                {{--</div>--}}
                            </div>
                            <div class="form-group">
                                {!!Form::label('porcent', 'Porcentaje:', array( 'class' => 'col-md-4 izq')) !!}
                                {{--<div class="col-sm-8">--}}
                                    {!!Form::text('porcentaje', Session::get('porcentaje') ,array('required', 'class' => 'form-control')) !!}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{--<a class="btn btn-default pull-left" href="{{URL::to('/')}}/profesor/cursos/{{$curso->id}}/secciones/{{$seccion}}/participantes/{{$participante->id}}/notas"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-success pull-right" ><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{--Fin Modal--}}

    {{--Modal para editar notas--}}
    <div class="modal fade" id="notasEditModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{--<a href="{{URL::to('/')}}/profesor/cursos/{{$curso->id}}/secciones/{{$seccion}}/participantes/{{$participante->id}}/notas" class="pull-right"> <span class="glyphicon glyphicon-remove" style="color: #333;"></span> </a>--}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4> Edición calificación </h4>
                </div>
                <div class="modal-body">
                    {!!Form::open(["url"=>"profesor/cursos/".$curso->id."/secciones/".$seccion."/participantes/".$participante->id."/notas",  "method" => "post" ])!!}
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 col-xs-12">
                            <div class="form-group" >
                                {!!Form::label('nombre', 'Evaluación:', array( 'class' => 'col-md-4 izq')) !!}
                                <input type="hidden" value="" id="id_nota" name="id_nota">
                                <div id="eval" ></div>
                            </div>
                            <div class="form-group" >
                                {!!Form::label('nota', 'Nota:', array( 'class' => 'col-md-4 izq')) !!}
                                <div id="calif"></div>
                            </div>
                            <div class="form-group" >
                                {!!Form::label('porcent', 'Porcentaje:', array( 'class' => 'col-md-4 izq')) !!}
                                <div id="porct" ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-success pull-right" ><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{--Fin Modal--}}


@stop