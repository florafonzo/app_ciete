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
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('roles.buscar'))) !!}
                        <div class="buscador">
                            <select class="form-control " name="parametro">
                                <option value="0"  selected="selected"> Buscar por</option>
                                <option value="name"  > Nombre</option>
                            </select>
                            {!!Form::text('busqueda', null,array( 'placeholder' => 'Escriba su busqueda...','class' => 'form-control'))!!}
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" ></span> </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Permisos</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($busq)
                            @if($roles != null)
                                @foreach($roles as $index => $rol)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
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
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @if($busq_)
                                    <td></td>
                                    <td> 0 resultados de la busqueda</td>
                                @else
                                    <td></td>
                                    <td>No existen cursos activos</td>
                                @endif
                            @endif
                        @else
                            @if($roles->count())
                                @foreach($roles as $index => $rol)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
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
                                        </td>
                                    </tr>
                                @endforeach

                            @else
                                @if($busq_)
                                    <td></td>
                                    <td> 0 resultados de la busqueda</td>
                                @else
                                    <td></td>
                                    <td>No existen cursos activos</td>
                                @endif
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
                @if(Entrust::can('crear_roles'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/roles/create" type="button" class="btn btn-success" >Crear Rol </a>
                    </div>
                @endif
            </div>
        @endif
    </div>



@stop