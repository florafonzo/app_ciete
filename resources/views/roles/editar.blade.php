@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Edición de Rol
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
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
                            @foreach($permisos as $index => $permiso)
                                @if ($perms[$index] == true)
                                    {!! Form::checkbox('permisos[]', $permiso, true) !!} {{$permiso}} <br>
                                @else
                                    {!! Form::checkbox('permisos[]', $permiso, false) !!} {{$permiso}} <br>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <a href="{{URL::to("/")}}/roles" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    {!! Form::submit('Editar', array('class' => 'btn btn-success')) !!}

                    {!! Form::close() !!}
                @endif
            </div>
        @endif
    </div>

@stop
