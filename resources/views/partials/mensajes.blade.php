@if (count($errors) > 0)
    {{--<div class="row">--}}
        <div class="errores ">
            <strong>Whoops!</strong> Hubo ciertos errores: <br><br>
            <ul class="lista_errores">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    {{--</div>--}}
@endif
@if ($errores != '')
    {{--<div class="row">--}}
        <div class="errores ">
            <strong>Whoops!</strong> Hubo ciertos errores: <br><br>
            <ul class="lista_errores">
                {{--@foreach ($errores->all() as $error)--}}
                <li>{{ $errores }}</li>
                {{--@endforeach--}}
            </ul>
        </div>
    {{--</div>--}}
@endif
@if(Session::has('mensaje'))
    <div id="" class='alert alert-success flash_time'>
        {{ Session::pull('mensaje') }}
    </div>
@endif
@if(Session::has('error'))
    <div class='alert errores'>
        <strong>Whoops!</strong> Hubo ciertos errores: <br><br>
        <ul class="lista_errores">
            <li>{{ Session::pull('error') }}</li>
        </ul>
    </div>
@endif
<div class="alert alert-warning" id="alerta_img" hidden>
    <strong>Cuidado!</strong> Debe colocar solo imagenes de tipo JPG o JPEG.
</div>