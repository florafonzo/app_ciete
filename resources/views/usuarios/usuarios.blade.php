@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Usuarios
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
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
                                            {!! Form::open(array('method' => 'DELETE', 'route' => array('usuarios.destroy', $user->id), 'id' => 'form_eliminar'.$user->id)) !!}
{{--                                                {!! Form::button('<span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'mostrarModal($user->id)'))!!}--}}
                                                <button type="button" onclick="mostrarModal('{{$user->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar" aria-hidden="true">
                                                    <span class="glyphicon glyphicon-trash" ></span>
                                                </button>
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
                @if(Entrust::can('crear_uauarios'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/usuarios/create" type="button" class="btn btn-success" >Agregar usuario </a>
                    </div>
                @endif
            </div>
        @endif
    </div>



@stop