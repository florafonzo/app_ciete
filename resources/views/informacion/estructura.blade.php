@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-12 descripcion_princ">
                    <h3>
                        Estructura
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <img src="{{URL::to('/')}}/images/estructura.jpg" class="img-responsive center-block">
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 parr_contacto">
                    <h4 class="tit_contacto"> Directorio del CIETE </h4>
                    <p>
                        El directorio está conformado por:
                        <div class="sangria">
                            <ul>
                                <li>
                                    Coordinación del centro: tendrá voz y voto para la toma de decisiones.
                                </li>
                                <li>
                                    Responsables de cada una de las unidades que conforman el centro: tendrán voz y voto para la toma de decisiones.
                                </li>
                                <li>
                                    Representantes ocasionales de las dependencias universitarias que en alianza con el centro impulsen las TIC en la
                                    Universidad: PAD, CEIDIS, CTICA u otros que por su naturaleza puedan participar en proyectos de interés para la institución.
                                </li>
                            </ul>
                        </div>
                    </p>
                    </br>
                    <h4 class="tit_contacto">Unidad de asesoría</h4>
                    <p style="text-indent: 20px;">
                        Será la responsable de brindar servicios de asesoría a los profesores y estudiantes  de la Facultad de Humanidades y Educación de la Universidad
                        de Los Andes, así como cualquier institución y/o organismo interno o externo a la universidad que requiera de orientación y ayuda para el
                        emprendimiento de nuevos proyectos que hagan uso de las Tecnologías de Información y Comunicación como medio o recurso para potenciar el proceso
                        de enseñanza y aprendizaje.
                    </p>
                    </br>
                    <h4 class="tit_contacto">Unidad de formación</h4>
                    <p style="text-indent: 20px;">
                        Tendrá a su cargo la planificación y desarrollo de una oferta permanente de cursos de formación, actualización y perfeccionamiento del uso de las
                        Tecnologías de Información y Comunicación en cada una de las áreas de la gestión educativa. Además de la gestión de los programas de estudio
                        responsabilidad del centro.
                    </p>
                    </br>
                    <h4 class="tit_contacto">Unidad de proyectos</h4>
                    <p style="text-indent: 20px;">
                        Será responsable de proponer y emprender proyectos que permitan abordar los nuevos desafíos pedagógicos que le subyacen al uso de las  Tecnologías
                        de Información y Comunicación.
                    </p>
                    </br>
                    <h4 class="tit_contacto">Unidad de producción</h4>
                    <p style="text-indent: 20px;">
                        Brindará servicios dentro y fuera de la universidad, para el diseño, producción y evaluación de recursos educativos en formato digital.
                    </p>
                    </br>
                    <h4 class="tit_contacto">Unidad de investigación</h4>
                    <p style="text-indent: 20px;">
                        Será la responsable de  proponer e impulsar proyectos micro y macros de investigación, en conjunto con estudiantes de pregrado y postgrado
                        que soporten las propuestas y proyectos de desarrollo del centro.

                    </p>
                    </br>
                    <h4 class="tit_contacto">Unidad de apoyo administrativo y técnico</h4>
                    <p style="text-indent: 20px;">
                        Será responsable de apoyar al centro en el desarrollo de las distintas tareas administrativas para garantizar su normal funcionamiento.
                        Además de brindar  apoyo técnico en el manejo de los equipos y/o laboratorio y redes del centro para el desarrollo de las actividades de
                        asesoría y formación.

                    </p>
                </div>
            </div>
        </div>
    </div>
@stop