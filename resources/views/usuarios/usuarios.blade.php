@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Bienvenido {{Auth::user()->nombre}} {{ Auth::user()->apellido }}
            </h3>
        </div>

        @if(Auth::user()->hasRole('admin'))
            <div class="col-md-4 col-sm-4 opciones_part">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-md-offset-3">
                        <img src="{{URL::to('/')}}/images/foto_participante.png">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 menu_part">
                        <ul>
                            <li>
                                <a href="{{URL::to('/usuarios')}}"> Usuarios </a>
                            </li>
                            <li>
                                <a href="#"> Lista de cursos </a>
                            </li>
                            <li>
                                <a href="#"> Información </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-8 opciones_part2">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    @if($usuarios->count())
                        <tbody>
                        @foreach($usuarios as $user)
                            <tr>
                                <td>{{ $user->nombre }}</td>
                                <td>{{ $user->apellido  }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->rol->display_name }}</td>
                                <td>
                                @if(Entrust::can('editar_usuarios'))
                                        <button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Editar"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> </button>
                                @endif
                                @if(Entrust::can('eliminar_usuarios'))
                                        <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Eliminar"> <span class="glyphicon glyphicon-trash  " aria-hidden="true"></span> </button>
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
            </div>
        @endif
    </div>

@stop