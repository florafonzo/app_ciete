@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Creación de Rol
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                {{--@if (count($errors) > 0)--}}
                    {{--<div class="row">--}}
                        {{--<div class="errores ">--}}
                            {{--<strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>--}}
                            {{--<ul class="lista_errores">--}}
                                {{--@foreach ($errors->all() as $error)--}}
                                    {{--<li>{{ $error }}</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
                {{--@if ($errores != '')--}}
                    {{--<div class="row">--}}
                        {{--<div class="errores ">--}}
                            {{--<strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados: <br><br>--}}
                            {{--<ul class="lista_errores">--}}
                                {{--@foreach ($errores->all() as $error)--}}
                                {{--<li>{{ $errores }}</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
                {!! Form::open(array('method' => 'POST', 'action' => 'RolesController@store', 'class' => 'form-horizontal col-md-10')) !!}

                <div class="form-group">
                    {!!Form::label('nombre', 'Nombre', array( 'class' => 'col-md-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('name', Session::get('nombre') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('descripcion', 'Descripción',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        {!!Form::textarea('descripcion', Session::get('descripcion') ,array('required','class' => 'form-control'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('permisos', 'Permisos',  array( 'class' => 'col-md-4 control-label'))!!}
                    <div class="col-sm-8">
                        @foreach($permisos as $permiso)
                            {!! Form::checkbox('permisos[]', $permiso, false) !!} {{$permiso}} <br>
                        @endforeach
                    </div>
                </div>
                <a href="{{URL::to("/")}}/roles" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                {!! Form::submit('Crear', array('class' => 'btn btn-success')) !!}

                {!! Form::close() !!}
            </div>
        @endif
    </div>

@stop
