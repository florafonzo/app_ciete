@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-12 descripcion_princ">
                    <h3>
                        Equipo CIETE
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <img src="{{URL::to('/')}}/images/equipo.png" class="img-responsive center-block">
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 ">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/R_Marquina.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class=""> Raymond Marquina </h4>
                            <p >
                                Director del Centro / Miembro fundador<br>
                                Profesor Asociado de la Escuela de Medios Audiovisuales<br>
                                Facultad de Humanidades y Educación<br>
                                Ver perfil en About.me
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/T_Perez.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class="">Teadira Perez</h4>
                            <p>
                                Miembro fundador<br>
                                Directora / Profesora Titular de la Escuela de Idiomas Modernos<br>
                                Facultad de Humanidades y Educación<br>
                                Ver perfil en About.me
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/F_Quinonez.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class="">Francisco Quiñonez</h4>
                            <p>
                                Miembro fundador<br>
                                Director / Profesor de la Escuela de Medios Audiovisuales<br>
                                Facultad de Humanidades y Educación
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/foto_equipo.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class=""> Ricardo Chetuan </h4>
                            <p>
                                Miembro fundador<br>
                                Profesor de la Escuela de Medios Audiovisuales<br>
                                Facultad de Humanidades y Educación
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/J_Perez.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class="">Jimena Pérez </h4>
                            <p>
                                Miembro del Centro<br>
                                Personal de la Coordinación de Estudios Interactivos a Distancia de la Universidad de Los Andes<br>
                                Profesora de la Carrera de Comunicación Social, Núcleo Mérida<br>
                                Escuela de Medios Audiovisuales. Facultad de Humanidades y Educación
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/E_Benitez.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class=""> Elisabeth Benitez</h4>
                            <p>
                                Miembro del Centro<br>
                                Investigadora activa en el área de las TIC
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/H_Picon.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class=""> Henry Picón</h4>
                            <p>
                                Miembro del Centro<br>
                                Jefe de la Oficina de Imagen Institucional y Diseño de la Universidad de Los Andes<br>
                                Ver perfil en About.me
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/A_Calero.png" class="img-responsive center-block">
                        </div>
                        <div class="col-sm-8">
                            <h4 class="">Ángel Calero</h4>
                            <p>
                                Miembro del Centro<br>
                                Profesor de la UPTM Kleiver Ramirez<br>
                                Investigador activo en el área de las TIC
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/A_Alvarado.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class="">Ángel Alvarado</h4>
                            <p>
                                Miembro invitado del Centro<br>
                                Profesor de la Universidad Central de Venezuela<br>
                                Director del CERI de la Escuela de Educación - UCV
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="{{URL::to('/')}}/images/Y_Hernandez.png" class="img-responsive center-block">
                        </div>
                        <div class="col-md-8">
                            <h4 class=""> Yosly Hernandez</h4>
                            <p>
                                Miembro invitado del Centro<br>
                                Profesora de la Universidad Central de Venezuela
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop