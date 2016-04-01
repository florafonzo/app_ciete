@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-2 col-xs-12 bienvenida">
            <h3>
                Bienvenido {{Auth::user()->nombre}} {{ Auth::user()->apellido }}
            </h3>
        </div>
        @include('partials.menu_usuarios')

        <div class="col-md-8 col-xs-12 opciones_part2">
            @include('partials.mensajes')

            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <h3 class="centro">Diplomados:</h3>
                    <div id="myCarousel1" class="carousel slide myCarousel_" data-ride="carousel">
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
                                                <img class="imgCarousel_" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$diplomado->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$diplomado->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="item">
                                            <a href="{{URL::to('/')}}/descripcion/curso/{{$diplomado->id}}">
                                                <img class="imgCarousel_" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$diplomado->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$diplomado->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="item">
                                    <img class="imgCarousel_" src="{{URL::to('/')}}/images/ciete2.png" >
                                    <div class="carousel-caption descripcion">
                                        <h4 class=""> Próximamente más Diplomados </h4>
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
                <div class="col-md-6 col-xs-12">
                    <h3 class="centro">Capsulas:</h3>
                    <div id="myCarousel2" class="carousel slide myCarousel_" data-ride="carousel">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            @if($capsulas != null)
                                @foreach($capsulas as $index => $capsula)
                                    @if($index == 0)
                                        <div class="item active">
                                            <a href="{{URL::to('/')}}/descripcion/curso/{{$capsula->id}}">
                                                <img class="imgCarousel_" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$capsula->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$capsula->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="item">
                                            <a href="{{URL::to('/')}}/descripcion/curso/{{$capsula->id}}">
                                                <img class="imgCarousel_" src="{{URL::to('/')}}/images/images_carrusel/cursos/{{$capsula->imagen_carrusel}}" >
                                            </a>
                                            <div class="carousel-caption descripcion">
                                                <h4 class=""> {{$capsula->descripcion_carrusel}} </h4>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="item">
                                    <img class="imgCarousel_" src="{{URL::to('/')}}/images/ciete2.png" >
                                    <div class="carousel-caption descripcion">
                                        <h4 class=""> Próximamente más Cápsulas </h4>
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
            </div>
            <div class="row">

            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-12">
                    <h3 class="centro">Webinars:</h3>
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
        </div>
    </div>

@stop