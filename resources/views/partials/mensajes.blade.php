@if (count($errors) > 0)
    <div class="row">
        <div class="errores ">
            <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>
            <ul class="lista_errores">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
@if ($errores != '')
    <div class="row">
        <div class="errores ">
            <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>
            <ul class="lista_errores">
                {{--@foreach ($errores->all() as $error)--}}
                <li>{{ $errores }}</li>
                {{--@endforeach--}}
            </ul>
        </div>
    </div>
@endif
@if(Session::has('mensaje'))
    <div id="" class='alert alert-success flash_time'>
        {{ Session::pull('mensaje') }}
    </div>
@endif
@if(Session::has('error'))
    <div class='alert alert-danger flash_time'>
        {{ Session::pull('error') }}
    </div>
@endif