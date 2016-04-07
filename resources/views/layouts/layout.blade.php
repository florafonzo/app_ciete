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
      <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/sweetalert.css">

      <link rel="stylesheet" href="{{asset('/')}}css/normalize.css" />
      <link rel="stylesheet" href="{{asset('/')}}css/demo.css" />
      <link rel="stylesheet" href="{{asset('/')}}css/component.css" />



	</head>
	<body>
        {{--Header con logo--}}
        @include('layouts.header')
        {{--Fin Header--}}

        {{--Contenedor--}}
		<div class="container">
            {{--Menú--}}
            @include('layouts.menu', ['menu'=>\App\Models\Curso::getCursos()])
            {{--Fin Menú--}}

            {{--Contenido Principal--}}
            @yield('content')
            {{--Fin Contenido Principal--}}

            {{--Footer--}}
            @include('layouts.footer')
            {{--Fin Footer--}}
        </div>
        <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
        <script>
            webshims.setOptions('forms-ext', {types: 'date'});
            webshims.polyfill('forms forms-ext');
        </script>
        <script src="{{URL::to('/')}}/js/jquery.js"></script>
        <script src="{{URL::to('/')}}/js/jquery-1.11.3.min.js"></script>
        <script src="{{URL::to('/')}}/js/jquery-ui.min.js"></script>
        <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="{{URL::to('/')}}/plugins/ckeditor/ckeditor.js"></script>
        <script src="{{URL::to('/')}}/js/component.js"></script>
        <script src="{{URL::to('/')}}/js/generic.js"></script>
        <script src="{{URL::to('/')}}/js/sweetalert.min.js"></script>

        <!--<script>
        $(document).ready(function(){
          alert('Holaaaaaaaa');
        });
      </script>-->
	</body>
</html>