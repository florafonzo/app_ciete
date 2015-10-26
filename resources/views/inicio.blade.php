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
            <div class="col-md-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">Ingresar</div>
                            <div class="panel-body">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> Hubo errores en sus datos.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                              <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form>
                                    <div class="form-group">
                                      <label class="col-xs-3 control-label">Email</label>
                                      <div class="col-xs-12">
                                          <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 control-label">Contraseña</label>
                                        <div class="col-xs-12">
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-9">
                                            <button type="submit" class="btn btn-primary">
                                              Ingresar
                                            </button>
                                            <a style="float: left" href="/password/email">¿Olvidaste de tu contraseña?</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
      </div>
  </div>
@stop
