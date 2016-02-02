@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Roles
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
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Permisos</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($roles->count())
                        <tbody>
                        @foreach($roles as $rol)
                            <tr>
                                <td>{{ $rol->display_name }}</td>
                                <td>
                                    @foreach($rol->permisos as $permiso)
                                        {{ $permiso->display_name }} <br/>
                                    @endforeach
                                </td>
                                <td>
                                    @if(Entrust::can('editar_roles'))
                                        {{--<button><span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span></button>--}}
                                        {!! Form::open(array('method' => 'GET','route' => array('roles.edit', $rol->id))) !!}
                                        {!! Form::button('<span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                                <td>

                                    {{--{{ $rol->display_name }} <br/>--}}
                                    @if(($rol->name == 'admin') or ($rol->name == 'coordinador') or ($rol->name == 'participante') or ($rol->name == 'profesor'))
                                        {!! Form::open(array('')) !!}
                                        {!! Form::button('<span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button','class' => 'btn btn-danger', 'disabled'))!!}
                                        {!! Form::close() !!}
                                    @else
                                        {!! Form::open(array('method' => 'DELETE', 'route' => array('roles.destroy', $rol->id), 'id' => 'form_eliminar'.$rol->id)) !!}
                                            {{--{!! Form::button('<span class="glyphicon glyphicon-trash" id="{{$rol->id}}" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#modal_eliminar_rol','class' => 'btn btn-danger'))!!}--}}
                                            <button type="button" onclick="mostrarModal('{{$rol->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar" aria-hidden="true">
                                                <span class="glyphicon glyphicon-trash" ></span>
                                            </button>
                                        {!! Form::close() !!}
                                    @endif

                                    {{--@if(Entrust::can('eliminar_roles'))--}}

                                        {{--{!! Form::open(array('method' => 'DELETE', 'route' => array('roles.destroy', $rol->id))) !!}--}}
                                        {{--{!! Form::button('<span class="glyphicon glyphicon-trash" id="{{$curso->id}}" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#modal_eliminar_rol','class' => 'btn btn-danger'))!!}--}}
                                        {{--{!! Form::close() !!}--}}
                                    {{--@endif--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
                @if(Entrust::can('crear_roles'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/roles/create" type="button" class="btn btn-success" >Crear Rol </a>
                    </div>
                @endif
            </div>
        @endif
    </div>



@stop