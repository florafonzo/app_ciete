@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Bienvenid@ {{Auth::user()->nombre}} {{ Auth::user()->apellido }}
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
                            <a href="#"> Ver Perfil </a>
                        </li>
                        <li>
                            <a href="#"> Historial de cursos </a>
                        </li>
                        <li>
                            <a href="#"> Obtener Certificado </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8">
            <div class="col-md-10 col-md-offset-1">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    {{--<ol class="carousel-indicators">--}}
                    {{--<li data-target="#myCarousel" data-slide-to="0" class="active"></li>--}}
                    {{--<li data-target="#myCarousel" data-slide-to="1"></li>--}}
                    {{--<li data-target="#myCarousel" data-slide-to="2"></li>--}}
                    {{--<li data-target="#myCarousel" data-slide-to="3"></li>--}}
                    {{--<li data-target="#myCarousel" data-slide-to="4"></li>--}}
                    {{--</ol>--}}

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <img src="{{URL::to('/')}}/images/diplo_car.jpg" >
                            <div class="carousel-caption descripcion">
                                <h4 class="">La segunda cohorte del Diplomado #GEMED inicia el 19 de Octubre</h4>
                            </div>
                        </div>

                        <div class="item">
                            <img src="{{URL::to('/')}}/images/caps2_car.png" >
                            <div class="carousel-caption descripcion">
                                <h4 class="">Segundo ciclo de C�psulas del Conocimiento</h4>
                            </div>
                        </div>

                        <div class="item">
                            <img src="{{URL::to('/')}}/images/diplo2_car.jpg" >
                            <div class="carousel-caption descripcion">
                                <h4 class="">Diplomado online Dise�o y producci�n de contenidos multimedia para la Web</h4>
                            </div>
                        </div>

                        <div class="item">
                            <img src="{{URL::to('/')}}/images/micro_car.jpg" >
                            <div class="carousel-caption descripcion">
                                <h4 class="">Micro-curso Podcasting - Ciclo #CapsulasConocimiento</h4>
                            </div>
                        </div>

                        <div class="item">
                            <img src="{{URL::to('/')}}/images/webinar_car.jpg" >
                            <div class="carousel-caption descripcion">
                                <h4 class="">Programaci�n de Webinars Octubre 2015</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control flechas" href="#myCarousel" role="button" data-slide="prev" style="background-color: #337ab7">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="right carousel-control flechas" href="#myCarousel" role="button" data-slide="next" style="background-color:#337ab7 ;">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

@stop