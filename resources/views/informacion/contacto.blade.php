@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-12 descripcion_princ">
                    <h3>
                        Contacto
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <img src="{{URL::to('/')}}/images/equipo.png" class="img-responsive center-block">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-1 ">
                    <h4 class="tit_contacto"> Dirección del CITETE: </h4>
                    <p >
                        Avenida Las Américas, al frente de la plaza de Toros Román Eduardo Sandia,<br>
                        Núcleo Universitario La Liria.<br>
                        Facultad de Humanidades y Educación.<br>
                        Edificio Foro-Café,<br>
                        Mérida, Venezuela
                    </p>
                    <br>
                    <h4 class="tit_contacto">Teléfonos de contacto:</h4>
                    <p>
                        <strong>Fijo:</strong> +58 274-4165221 en horario de oficina (8am a 12m y 2pm a 6pm)<br>
                        <strong>Móvil:</strong>  +58 412 6834340 (Vía WhatsApp)
                    </p>
                    <br>
                    <h4 class="tit_contacto">Correo electrónico: </h4>
                    <p>
                        <strong>Institucional:</strong> <a class="correo" href="mailto:ciete@ula.ve" target="_top">ciete@ula.ve</a><br>
                        <strong>Gmail:</strong> <a class="correo" href="mailto:cieteula@gmail.com" target="_top">ciete.ula@gmail.com</a>
                    </p>
                    <br>
                    <h4 class="tit_contacto">Redes sociales:</h4>
                    <p>
                        <strong>Twitter:</strong>  <a href="https://twitter.com/cieteula">cieteula</a> <br>
                        <strong>Instagram:</strong> <a href="https://instagram.com/ciete.ula/">ciete.ula</a><br>
                        <strong>Facebook:</strong> <a href="https://www.facebook.com/ciete.ula">ciete.ula</a><br>
                    </p>
                </div>
                <div class="col-md-4">
                    <form id="form_contacto" action="">
                        <h4 class="tit_contacto">Formulario de contacto:</h4>
                        <p class="parr_contacto">
                            Utilice este formulario para comunicarse con el personal del Centro. No olvide escribir su comentario, duda o
                            inquietud en forma clara. Escriba correctamente su dirección de correo electrónico para poder dar respuesta
                            a la mayor brevedad posible
                        </p>
                        <div class="form-group">
                            <label for="nombre_contacto">Nombre:</label>
                            <input type="text" class="form-control" id="nombre_contacto" placeholder="Nombre">
                        </div>
                        <div class="form-group">
                            <label for="apellido_contacto">Apellido:</label>
                            <input type="text" class="form-control" id="apellido_contacto" placeholder="Apellido">
                        </div>
                        <div class="form-group">
                            <label for="lugar_contacto">Lugar de procedencia:</label>
                            <input type="text" class="form-control" id="lugar_contacto" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="correo_contacto">Lugar de procedencia:</label>
                            <input type="email" class="form-control" id="correo_contacto" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="duda_contacto">Comentario, duda o inquietud:</label>
                            <textarea name="comentario" form="form_contacto" class="form-control" id="duda_contacto" placeholder="Duda..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop