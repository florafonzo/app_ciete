@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-12 descripcion_princ">
                    <h3>
                        Servicios
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <img src="{{URL::to('/')}}/images/servicios.jpg" class="img-responsive center-block">
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 parr_contacto">
                    <h4 class="tit_contacto"> Asesoría </h4>
                    <p style="text-indent: 20px;">
                        Asesoría a los profesores y estudiantes  de la Facultad de Humanidades y Educación de la Universidad de
                        Los Andes, así como cualquier institución y/o organismo interno o externo a la universidad que requiera de
                        orientación y ayuda para el emprendimiento de nuevos proyectos que hagan uso de las Tecnologías de Información
                        y Comunicación como medio o recurso para potenciar el proceso de enseñanza y aprendizaje.
                    </p>
                    </br>
                    <h4 class="tit_contacto">Cursos</h4>
                    <p style="text-indent: 20px;">
                        Cursos de formación, actualización y perfeccionamiento del uso de las Tecnologías de Información y Comunicación en
                        cada una de las áreas de la gestión educativa
                    </p>
                    </br>
                    <h4 class="tit_contacto">Emprendimiento</h4>
                    <p style="text-indent: 20px;">
                        Emprendimiento de proyectos que permitan abordar los nuevos desafíos pedagógicos que le subyacen al uso de las
                        Tecnologías de Información y Comunicación.
                    </p>
                    </br>
                    <h4 class="tit_contacto">Recursos educativos</h4>
                    <p style="text-indent: 20px;">
                        Diseño, producción y evaluación de recursos educativos en formato digital.
                    </p>
                    </br>
                    <h4 class="tit_contacto">Proyectos</h4>
                    <p style="text-indent: 20px;">
                        Proyectos micro y macros de investigación, en conjunto con estudiantes de pregrado y postgrado de diferentes escuelas
                        y facultades de la Universidad de Los Andes, que soporten las propuestas y proyectos de desarrollo del centro.
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop