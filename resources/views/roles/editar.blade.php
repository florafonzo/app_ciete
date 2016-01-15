@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Edición de Rol
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('layouts.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
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
                @if($roles->count())
                    {!! Form::open(array('method' => 'PUT', 'route' => array('roles.update', $roles->id), 'class' => 'form-horizontal col-md-12')) !!}

                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre', array( 'class' => 'col-md-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('name', $roles->name ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('desc', 'Descripción',  array( 'class' => 'col-md-4 control-label'))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('descripcion', $roles->description ,array('required','class' => 'form-control'))!!}
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

                    {!! Form::submit('Editar', array('class' => 'btn btn-success')) !!}

                    {!! Form::close() !!}
                @endif
            </div>
        @endif
    </div>

@stop
