@extends('layouts.layout')

@section('content')

    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
      <div class="col-md-12 col-sm-12">
        <div class="row">
            <div class="col-md-12 col-sm-12 descripcion_princ">
                <h4>Bienvenid@ al Portal de cursos que ofrece el Centro Innovación y Emprendimiento para el uso
                    de Tecnologías en Educación de la Universidad de los Andes.<br>
                    El Centro ofrece asesoría a los estudiantes y profesores de la universidad, Cursos de formación,
                    actualización y perfeccionamiento del uso de las TIC, promueve el Emprendimiento de proyectos y diseña, produce y evalúa recursos
                    educativos en formato digital.
                </h4>
            </div>
        </div>
        <div class="row">
            @if (Auth::guest())
                <div class="col-md-8">
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
                                    <h4 class="">Segundo ciclo de Cápsulas del Conocimiento</h4>
                                </div>
                            </div>

                            <div class="item">
                                <img src="{{URL::to('/')}}/images/diplo2_car.jpg" >
                                <div class="carousel-caption descripcion">
                                    <h4 class="">Diplomado online Diseño y producción de contenidos multimedia para la Web</h4>
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
                                    <h4 class="">Programación de Webinars Octubre 2015</h4>
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
                {{--<div class="col-md-4">--}}
                <aside class="col-md-4 col-sm-12 twit">
                    {{--<h4>Twitter</h4>--}}
                    <a class="twitter-timeline" href="https://twitter.com/cieteula" data-widget-id="660117846270337024">Tweets por el @cieteula.</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

                </aside>
            @else
                @include('usuarios.principal')
            @endif
        </div>
      </div>
  </div>
@stop
