@extends('layouts.layout')

@section('content')
	<div class="container-fluid">
        <div class="row paneles">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel_login">
                    <div class="panel-heading">Formulario de preinscripción</div>
                    <div class="panel-body">
                        @include('partials.mensajes'){{--Errores--}}
                        <!-- @if (count($errors) > 0)
                            <div class="alert errores">
                                <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados:<br><br>
                                <ul class="">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                         @endif-->

                        <form id="form" method="POST" action="{{ url('Contacto') }}">

                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre">
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido:</label>
                                <input type="text" class="form-control" id="apellido" placeholder="Apellido" name="apellido">
                            </div>
                            
                            <div class="form-group">
                            	<label for="curso">Curso:</label>
                            	<select name="curso" id="curso" placeholder="Curso">
                                <option value="0"  selected="selected"> Seleccione un curso</option>
                                    @foreach($cursos as $curso)
                                        <option value="id">{{$curso}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="correo">Correo eléctronico:</label>
                                <input type="email" class="form-control" id="correo" placeholder="Email" name="correo">
                            </div>
                            <!--
                            <div class="form-group">
                                <label for="duda">Comentario, duda o inquietud:</label>
                                <textarea name="comentario" form="form" class="form-control" id="duda" placeholder="Comentario, duda o inquietud..."></textarea>
                            </div>
                        	-->
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
			</div>
        </div>
	</div>
@stop