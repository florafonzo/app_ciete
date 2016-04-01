@extends('layouts.layout')

@section('content')

    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-xs-12">
            <div class="row">
                <div class="col-md-12 col-xs-12 descripcion_princ">
                    <h4>
                        Bienvenid@ al Portal de cursos que ofrece el Centro Innovación y Emprendimiento para el uso
                        de Tecnologías en Educación de la Universidad de los Andes.<br>
                        El Centro ofrece asesoría a los estudiantes y profesores de la universidad, cursos de formación,
                        actualización y perfeccionamiento del uso de las TIC, promueve el Emprendimiento de proyectos y diseña, produce y evalúa recursos
                        educativos en formato digital.
                    </h4>
                </div>
            </div>
            @if (Auth::guest())
            <div class="row">
                <div class="col-md-6">
                    <h2 class="centro titre">Diplomados:</h2>
                    <div id="myCarousel1" class="carousel slide myCarousel" data-ride="carousel">
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
                            @if($diplomados != null)
                                @foreach($diplomados as $index => $diplomado)
                                    @if($index == 0)
                                        <div class="item active Carousel">
                                            <a href="{{URL::to('/')}}/descripcion/curso/{{$diplomado->id}}">
                                                <img class="imgCarousel" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$diplomado->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$diplomado->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="item">
                                            <a href="{{URL::to('/')}}/descripcion/curso/{{$diplomado->id}}">
                                                <img class="imgCarousel" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$diplomado->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$diplomado->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="item active">
                                    <img class="imgCarousel" src="{{URL::to('/')}}/images/ciete2.png" >
                                    <div class="carousel-caption descripcion">
                                        <h4 class="">Proximamente más Diplomados</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Left and right controls -->
                        <a class="left carousel-control flechas" href="#myCarousel1" role="button" data-slide="prev" style="background-color: #337ab7">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="right carousel-control flechas" href="#myCarousel1" role="button" data-slide="next" style="background-color:#337ab7 ;">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>
                </div>
                <aside class="col-md-4 col-md-offset-1 col-sm-12 twit">
                    <h2 class="centro titre">Twitter:</h2>
                    {{--<h4>Twitter</h4>--}}
                    <a class="twitter-timeline" href="https://twitter.com/cieteula" data-widget-id="660117846270337024">Tweets por el @cieteula.</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </aside>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h2 class="centro titre">Capsulas:</h2>
                    <div id="myCarousel2" class="carousel slide myCarousel" data-ride="carousel">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            @if($capsulas != null)
                                @foreach($capsulas as $index => $capsula)
                                    @if($index == 0)
                                        <div class="item active">
                                            <a href="{{URL::to('/')}}/descripcion/curso/{{$capsula->id}}">
                                                <img class="imgCarousel" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$capsula->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$capsula->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="item">
                                            <a href="{{URL::to('/')}}/descripcion/curso/{{$capsula->id}}">
                                                <img class="imgCarousel" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$capsula->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$capsula->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="item active">
                                    <img class="imgCarousel" src="{{URL::to('/')}}/images/ciete2.png" >
                                    <div class="carousel-caption descripcion">
                                        <h4 class="">Proximamente más Cápsulas</h4>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Left and right controls -->
                        <a class="left carousel-control flechas" href="#myCarousel2" role="button" data-slide="prev" style="background-color: #337ab7">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="right carousel-control flechas" href="#myCarousel2" role="button" data-slide="next" style="background-color:#337ab7 ;">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>
                </div>
                <aside class="col-md-4 col-md-offset-1 col-sm-12 ">
                    <h2 class="centro titre">Instagram:</h2>
                    <!-- www.intagme.com -->
                    <div class="fondo_">
                        <iframe class="insta" src="http://www.intagme.com/in/?u=Y2lldGUudWxhfHNsfDMwMHwyfDN8fHllc3w1fHVuZGVmaW5lZHx5ZXM="  allowTransparency="true" frameborder="0" scrolling="no" ></iframe>
                    </div>
                </aside>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h2 class="centro titre">Webinars:</h2>
                    <div id="myCarousel3" class="carousel slide myCarousel" data-ride="carousel">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            @if($webinars->count())
                                @foreach($webinars as $index => $webinar)
                                    @if($index == 0)
                                        <div class="item active">
                                            <a href="{{URL::to('/')}}/descripcion/webinar/{{$webinar->id}}">
                                                <img class="imgCarousel" src="{{URL::to('/')}}/images/images_carrusel/webinars/{{$webinar->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$webinar->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="item">
                                            <a href="{{URL::to('/')}}/descripcion/webinar/{{$webinar->id}}">
                                                <img class="imgCarousel" src="{{URL::to('/')}}/images/images_carrusel/webinars/{{$webinar->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$webinar->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="item active">
                                    <img class="imgCarousel" src="{{URL::to('/')}}/images/ciete2.png" >
                                    <div class="carousel-caption descripcion">
                                        <h4 class="">Proximamente más Webinars</h4>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Left and right controls -->
                        <a class="left carousel-control flechas" href="#myCarousel3" role="button" data-slide="prev" style="background-color: #337ab7">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="right carousel-control flechas" href="#myCarousel3" role="button" data-slide="next" style="background-color:#337ab7 ;">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">

            </div>
            @else
                <div class="row">
                    @include('usuarios.principal')
                </div>
            @endif
        </div>
    </div>
@stop
