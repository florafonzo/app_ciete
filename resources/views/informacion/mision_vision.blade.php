@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-xs-12 descripcion_princ">
                    <h3>
                        ¿ Quiénes somos ?
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <img src="{{URL::to('/')}}/images/ciete3.png" class="img-responsive center-block">
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 parr_contacto">
                    <p>
                        CIETE es el Centro de Innovación y Emprendimiento para el uso de las Tecnologías en Educación el cual forma parte de la
                        Universidad de Los Andes situada en Mérida, Venezuela.
                    <p>
                        El uso de las Tecnologías de Información y Comunicación (TIC) por docentes a nivel universitario constituye un paso que la mayoría
                        de las instituciones universitarias a nivel nacional e internacional han dado. Apesar de la rapidez con que han avanzado y se incorporan al quehacer cotidiano del ser humano,
                        en el ámbito educativo su integración no ha sido tan inmediata. El uso de las TIC y su integración pedagógica como apoyo a las actividades presenciales benefician tanto a docentes
                        como a estudiantes. La combinación de actividades virtuales y presenciales a nivel universitario, pueden ofrecer a los estudiantes materiales
                        auténticos que se encuentran archivados electrónicamente y que proporciona oportunidades adicionales para reflexionar, interactuar y discutir
                        sobre un tema en particular, llevando así el conocimiento más allá del aula de clase.
                    </p>
                    <p>
                        Para poder integrar las TIC en el ámbito educativo se requiere un ente que coordine y ejecute de manera sistémica un plan con metas a corto, mediano y
                        largo plazo en cada una de las áreas propuestas. Es por ello que se propone la creación de un Centro dedicado específicamente a las tareas y actividades
                        que requieren atención inmediata según el diagnóstico realizado.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 parr_contacto">
                    <p>
                        La <strong>misión</strong> del centro es contribuir al desarrollo y fortalecimiento de una educación de calidad que utilice las tecnologías como recurso
                        y/o medio para potenciar los procesos de enseñanza y aprendizaje.
                    </p>
                    <p>
                        Su <strong>visón</strong> es ser un centro de emprendimiento, investigación e innovación, ampliamente reconocido por la capacidad creativa de su talento humano,
                        su contribución al desarrollo de las tecnologías en el campo educativo y su impacto en la universidad y el país.
                    </p>
                    <p>
                        Y finalmente el <strong>objetivo</strong> del centro es ofrecer servicios de asesoría, capacitación, soporte y desarrollo de recursos digitales así como de
                        adopción y facilitación de estrategias didácticas dentro y fuera de la Universidad de Los Andes. Fortalecer las competencias del talento humano con visión
                        innovadora del uso y aplicación de las tecnologías en el campo educativo. Impulsar el desarrollo de proyectos que permitan abordar los nuevos desafíos pedagógicos
                        que le subyacen al uso de las Tecnologías de Información y Comunicación. Brindar una oferta permanente de cursos de capacitación y actualización (en forma presencial y
                        virtual) en los usos de las tecnologías de información y comunicación en el ámbito educativo.
                    </p>
                </div>
            </div>


        </div>
    </div>
@stop