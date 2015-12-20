<!DOCTYPE html>
<html lang="en">
<head>
	  <title>CIETE</title>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="{{URL::to('/')}}/css/style.css">
	  <link rel="stylesheet" href="{{URL::to('/')}}/css/AdminLTE.min.css">
	  <link rel="stylesheet" href="{{URL::to('/')}}/css/bootstrap.css">
	  <link rel="stylesheet" href="{{URL::to('/')}}/css/bootstrap-3.3.5.css">


      <script src="{{URL::to('/')}}/js/jquery.js"></script>
      <script src="{{URL::to('/')}}/js/jquery-1.11.3.min.js"></script>
      <script src="{{URL::to('/')}}/js/jquery-ui.min.js"></script>
      {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>--}}
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

      <script src="{{URL::to('/')}}/js/generic.js"></script>
      <!--<script>
      $(document).ready(function(){
        alert('Holaaaaaaaa');
      });
    </script>-->

	</head>
	<body>
		<div class="container-fluid">
			<div class="row paneles">
				<div class="col-md-8 col-md-offset-2">
					<div class="panel panel-login">
					<div class="panel-body">
						<img src="{{ $message->embed(public_path() . '/images/ciete_logo.jpg') }}" width="60" height="80" />
						Estimado Usuario, <br>

						Haz click en el siguiente enlace para cambiar tu contraseña: <br>
					<form class="form-horizontal" role="form" method="GET" action="{{ url('password/reset/'.$token) }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Cambiar Contraseña
								</button>
							</div>
						</div>
					</form>
					</div>	
				</div>
			</div>
		</div>	
	</div>
	</body>
</html>