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
    <div id="flash_success" class='alert alert-success'>
        {{ Session::pull('mensaje') }}
    </div>
@endif
@if(Session::has('error'))
    <div class='alert alert-danger'>
        {{ Session::pull('error') }}
    </div>
@endif