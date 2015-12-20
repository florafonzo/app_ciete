@extends('layouts.layout')

@section('content')
<div class="container-fluid">
	<div class="row paneles">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel_login">
				<div class="panel-heading">Registro</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> Hubo algunos errores en sus datos.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Nombre</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="nombre" value="{{ old('name') }}" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Apellido</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="apellido" value="{{ old('name') }}" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Documento de Identidad</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="di" value="{{ old('name') }}" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Telefono de contacto</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="telefono" value="{{ old('name') }}" >
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Correo electronico</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Contraseña</label>
							<div class="col-md-6">
								<input type="password" id="password" class="form-control" name="password" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirme su contraseña</label>
							<div class="col-md-6">
								<input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" id="boton" class="btn btn-primary" >
									Guardar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
