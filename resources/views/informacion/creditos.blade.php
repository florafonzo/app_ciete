@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-12 descripcion_princ">
                    <h3>
                        Créditos
                    </h3>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <h4 class="titulo"> Trabajo Especial de Grado Desarrollado en la Universidad Central de Venezuela</h4>
                <div class="row">
                    <div class="col-md-4 ">
                        <a href="http://www.ucv.ve/" class="thumbnail logos alto">
                            <img src="{{URL::to('/')}}/images/logo_ucv.png" class="img-responsive">
                        </a>
                        <h5 class="titulo"> Universidad Central de Venezuela </h5>
                    </div>
                    <div class="col-md-4 ">
                        <a href="http://www.ciens.ucv.ve/ciens/" class="thumbnail logos alto">
                            <img src="{{URL::to('/')}}/images/logociens.jpg" class="img-responsive">
                        </a>
                        <h5 class="titulo"> Facultad de Ciencias </h5>
                    </div>
                    <div class="col-md-4 ">
                        <a href="http://computacion.ciens.ucv.ve/escueladecomputacion/" class="thumbnail logos alto ">
                            <img src="{{URL::to('/')}}/images/logo_comp.png" class="img-responsive">
                        </a>
                        <h5 class="titulo"> Escuela de Computación </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <h4 class="titulo"> Equipo de desarrollo</h4>
                <div class="row">
                    <div class="col-md-3 ">
                        <div class="thumbnail">
                            <img src="{{URL::to('/')}}/images/avatar-female.jpg" class="img-responsive">
                            <div class="caption">
                                <h5 class="nombre">Flor Afonzo</h5>
                                <p class="parr_contacto">
                                    Estudiante de la Licenciatura en Computación de La Facultad de
                                    Ciencias de La Universidad Central de Venezuela
                                    <br>
                                    <h5 class="oficio">Desarrolladora y Diseñadora del Portal</h5>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="thumbnail">
                            <img src="{{URL::to('/')}}/images/avatar-male.png" class="img-responsive">
                            <div class="caption">
                                <h5 class="nombre"> Abraham Díaz</h5>
                                <p class="parr_contacto">
                                    Estudiante de la Licenciatura en Computación de La Facultad de
                                    Ciencias de La Universidad Central de Venezuela.
                                    <br>
                                    <h5 class="oficio">Desarrollador y Diseñador del Portal</h5>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="thumbnail">
                            <img src="{{URL::to('/')}}/images/avatar-female.jpg" class="img-responsive">
                            <div class="caption">
                                <h5 class="nombre"> Yosly Hernández</h5>
                                <p class="parr_contacto">
                                    Profesora de la Licenciatura en Computación de La Facultad de
                                    Ciencias de La Universidad Central de Venezuela.
                                    <br>
                                    <h5 class="oficio">Tutora</h5>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="thumbnail">
                            <img src="{{URL::to('/')}}/images/avatar-male.png" class="img-responsive">
                            <div class="caption">
                                <h5 class="nombre"> Raymond Marquina </h5>
                                <p class="parr_contacto">
                                    Director del Centro,
                                    Profesor Asociado de la Escuela de Medios Audiovisuales de la
                                    Facultad de Humanidades y Educación.
                                    <br>
                                    <h5 class="oficio">Tutor</h5>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop