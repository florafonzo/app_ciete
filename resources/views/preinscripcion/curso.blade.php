@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="row paneles">
            <div class="col-md-8 col-md-offset-2">
                @include('partials.mensajes')
                <div class="panel panel_login">
                    <div class="panel-heading">Formulario de preinscripción de Diplomados y Cápsulas</div>
                    <div class="panel-body">
                        <form id="form" method="POST" action="{{ url('preinscripcion/cursos') }}">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido:</label>
                                <input type="text" class="form-control" id="apellido" placeholder="Apellido" name="apellido" required>
                            </div>

                            <div class="form-group">
                                <label for="curso">Curso:</label>
                                <select name="curso" id="curso" placeholder="Curso" required>
                                    <option value="0"  selected="selected">Seleccione un curso</option>
                                    @foreach($cursos as $index=>$curso)
                                        <option value="{{$index}}">{{$curso}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cedula">Cédula o Pasaporte:</label>
                                <input name="cedula" type="file">
                            </div>
                            <div class="form-group">
                                <label for="titulo">Título universitario:</label>
                                <input name="titulo" type="file">
                            </div>
                            <div>
                                <label for="recibo">Recibo de pago:</label>
                                <input name="recibo" type="file">
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo eléctronico:</label>
                                <input type="email" class="form-control" id="correo" placeholder="Email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop