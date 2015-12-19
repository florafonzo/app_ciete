@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Bienvenido {{Auth::user()->nombre}} {{ Auth::user()->apellido }}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('layouts.menu_usuarios')
            {{--<div class="col-md-4 col-sm-4 opciones_part">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-6 col-sm-6 col-md-offset-3">--}}
                        {{--<img src="{{URL::to('/')}}/images/foto_participante.png">--}}
                    {{--</div>--}}

                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-12 col-sm-12 menu_part">--}}
                        {{--<ul class="nav nav-pills nav-stacked">--}}
                            {{--<li class="active menu_usuarios">--}}
                                {{--<a style="text-decoration:none;" href="{{URL::to('/usuarios')}}"> Usuarios </a>--}}
                            {{--</li>--}}
                            {{--<li class="menu_usuarios">--}}
                                {{--<a style="text-decoration:none;" href="{{URL::to('/cursos')}}"> Lista de cursos </a>--}}
                            {{--</li>--}}
                            {{--<li class="menu_usuarios">--}}
                                {{--<a href="#"> Carrusel </a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-8 col-sm-8 opciones_part2">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                    </thead>
                    @if($usuarios->count())
                        <tbody>
                        @foreach($usuarios as $user)
                            <tr>
                                <td>{{ $user->nombre }}</td>
                                <td>{{ $user->apellido  }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $rol)
                                        {{ $rol->display_name }} <br/>
                                    @endforeach
                                </td>
                                <td>
                                @if(Entrust::can('editar_usuarios'))
                                    {!! Form::open(array('method' => 'GET','route' => array('usuarios.edit', $user->id))) !!}
                                        {!! Form::button('<span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}
                                    {!! Form::close() !!}
                                @endif
                                </td>
                                <td>
                                @if(Entrust::can('eliminar_usuarios'))
                                    @foreach($user->roles as $rol)
                                        {{--{{ $rol->display_name }} <br/>--}}
                                        @if($rol->name == 'admin')
                                            {!! Form::open(array('method' => 'DELETE', 'route' => array('usuarios.destroy', $user->id), 'id' => 'form_eliminar')) !!}
                                                {!! Form::button('<span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button','class' => 'btn btn-danger', 'disabled'))!!}
                                            {!! Form::close() !!}
                                        @else
                                            {!! Form::open(array('method' => 'DELETE', 'route' => array('usuarios.destroy', $user->id), 'id' => 'form_eliminar')) !!}
                                                {!! Form::button('<span class="glyphicon glyphicon-trash" id="{{$user->id}}" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#modal_eliminar','class' => 'btn btn-danger'))!!}
                                            {!! Form::close() !!}
                                        @endif

                                        <?php break; ?>

                                    @endforeach
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
                <div class="" style="text-align: center;">
                    <a href="{{URL::to('/')}}/usuarios/create" type="button" class="btn btn-success" >Agregar usuario </a>
                </div>

                {{-- Fin Modal de Eliminar--}}
                <div class="modal fade" id="modal_eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Eliminación de usuario</h4>
                            </div>
                            <div class="modal-body">
                                <h5>¿ Está usted seguro de que desea eliminar este usuario ?</h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button id="eliminar" type="button" class="btn btn-danger">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fin Modal de Eliminar--}}

            </div>
        @endif
    </div>



@stop