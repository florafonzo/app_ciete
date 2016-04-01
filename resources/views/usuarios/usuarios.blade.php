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
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('usuarios.buscar'), 'id' => 'form_busq')) !!}
                            <div class="buscador">
                                <select class="form-control " id="param" name="parametro">
                                    <option value="0"  selected="selected">Buscar por</option>
                                    <option value="apellido"  >Apellido</option>
                                    <option value="documento_identidad"  >Dcocumento de identidad</option>
                                    <option value="email"  >Correo</option>
                                    <option value="rol"  >Rol</option>
                                </select>
                                {!!Form::text('busqueda', null,array('placeholder' => 'Escriba su busqueda...','class' => 'form-control bus', 'id' => 'busqueda'))!!}
                                {!! Form::select('busqu', $roles, null, array('class' => 'form-control busq','hidden' => 'true', 'id' => 'busqueda2')) !!}
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
                            <th>Apellido</th>
                            <th>Documento de identidad</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($busq)
                            @if($usuarios != null)
                                @foreach($usuarios as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user[0]->nombre }}</td>
                                        <td>{{ $user[0]->apellido  }}</td>
                                        <td>{{ $user[0]->doc_id  }}</td>
                                        <td>{{ $user[0]->email }}</td>
                                        <td>
                                            @foreach($user[0]->roles as $rol)
                                                {{ $rol->display_name }} <br/>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if(Entrust::can('editar_usuarios'))
                                                {!! Form::open(array('method' => 'GET','route' => array('usuarios.edit', $user[0]->id))) !!}
                                                {!! Form::button('<span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="bottom" title="Editar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td>
                                            @if(Entrust::can('eliminar_usuarios'))
                                                @foreach($user[0]->roles as $rol)
                                                    {{--{{ $rol->display_name }} <br/>--}}
                                                    @if($rol->name == 'admin')
                                                        {!! Form::open(array('method' => 'DELETE', 'route' => array('usuarios.destroy', $user[0]->id), 'id' => 'form_eliminar')) !!}
                                                        {!! Form::button('<span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button','class' => 'btn btn-danger', 'disabled'))!!}
                                                        {!! Form::close() !!}
                                                    @else
                                                        {!! Form::open(array('method' => 'DELETE', 'route' => array('usuarios.destroy', $user[0]->id), 'id' => 'form_eliminar'.$user[0]->id)) !!}
                                                        {{--                                                {!! Form::button('<span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" title="Eliminar" aria-hidden="true"></span>', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'mostrarModal($user->id)'))!!}--}}
                                                        <button type="button" onclick="mostrarModal('{{$user[0]->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar" aria-hidden="true">
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
                            @if($usuarios->count())
                                @foreach($usuarios as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->nombre }}</td>
                                        <td>{{ $user->apellido  }}</td>
                                        <td>{{ $user->doc_id }}</td>
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
                @if(Entrust::can('crear_usuarios'))
                    <div class="" style="text-align: center;">
                        <a href="{{URL::to('/')}}/usuarios/create" type="button" class="btn btn-success" >Agregar usuario </a>
                    </div>
                @endif
            </div>
        @endif
    </div>



@stop