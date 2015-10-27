@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Bienvenid@ Nombre Apellido
            </h3>
        </div>
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
                            Ver Pefil
                        </li>
                        <li>
                            Cursos
                        </li>
                        <li>
                            Notas
                        </li>
                        <li>
                            Listas de participantes
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@stop