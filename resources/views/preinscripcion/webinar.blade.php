@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="row paneles">
            <div class="col-md-8 col-md-offset-2">
                @include('partials.mensajes')
                <div class="panel panel_login">
                    <div class="panel-heading">Formulario de preinscripción de Webinar</div>
                    <div class="panel-body">
                        <form id="form" method="POST" action="{{ url('preinscripcion/webinars') }}">

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
                                <label for="webinar">Webinar:</label>
                                <select name="curso" id="curso" placeholder="Webinar" required>
                                    <option value="0"  selected="selected">Seleccione un webinar</option>
                                    @foreach($webinars as $index=>$web)
                                        <option value="{{$index}}">{{$web}}</option>
                                    @endforeach
                                </select>
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