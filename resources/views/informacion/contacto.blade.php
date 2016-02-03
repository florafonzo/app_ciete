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
            @if($show == 'true')
                <div class="alert alert-success" id="flash_success">
                    <strong>¡Genial!</strong> Su mensaje ha sido enviado con satisfactoriamente.
                </div>
            @endif
            <div class="row">
                <div class="">
                    <img src="{{URL::to('/')}}/images/equipo.png" class="img-responsive center-block">
                </div>
            </div>


            <div class="row">
                <div class="col-md-6 col-md-offset-1 ">
                    <h4 class="tit"> Dirección del CIETE: </h4>
                    <p >
                        Avenida Las Américas, al frente de la plaza de Toros Román Eduardo Sandia,<br>
                        Núcleo Universitario La Liria.<br>
                        Facultad de Humanidades y Educación.<br>
                        Edificio Foro-Café,<br>
                        Mérida, Venezuela
                    </p>
                    <br>
                    <h4 class="tit">Teléfonos de contacto:</h4>
                    <p>
                        <strong>Fijo:</strong> +58 274-4165221 en horario de oficina (8am a 12m y 2pm a 6pm)<br>
                        <strong>Móvil:</strong>  +58 412 6834340 (Vía WhatsApp)
                    </p>
                    <br>
                    <h4 class="tit">Correo electrónico: </h4>
                    <p>
                        <strong>Institucional:</strong> <a class="correo" href="mailto:ciete@ula.ve" target="_top">ciete@ula.ve</a><br>
                        <strong>Gmail:</strong> <a class="correo" href="mailto:cieteula@gmail.com" target="_top">ciete.ula@gmail.com</a>
                    </p>
                    <br>
                    <h4 class="tit">Redes sociales:</h4>
                    <p>
                        <strong>Twitter:</strong>  <a href="https://twitter.com/cieteula">cieteula</a> <br>
                        <strong>Instagram:</strong> <a href="https://instagram.com/ciete.ula/">ciete.ula</a><br>
                        <strong>Facebook:</strong> <a href="https://www.facebook.com/ciete.ula">ciete.ula</a><br>
                    </p>
                </div>
                <div class="col-md-4">

                    <h4 class="tit">Formulario de contacto:</h4>
                        <p class="parr">
                            Utilice este formulario para comunicarse con el personal del Centro. No olvide escribir su comentario, duda o
                            inquietud en forma clara. Escriba correctamente su dirección de correo electrónico para poder dar respuesta
                            a la mayor brevedad posible.
                            <br>
                        </p>

                         @if (count($errors) > 0)
                            <div class="alert errores">
                                <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados:<br><br>
                                <ul class="">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                         @endif

                    <form id="form" method="POST" action="{{ url('Contacto') }}">

                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre">
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" placeholder="Apellido" name="apellido">
                        </div>
                        <div class="form-group">
                            <label for="lugar">Lugar de procedencia:</label>
                            <input type="text" class="form-control" id="lugar" placeholder="Lugar de procedencia" name="lugar">
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo eléctronico:</label>
                            <input type="email" class="form-control" id="correo" placeholder="Email" name="correo">
                        </div>
                        <div class="form-group">
                            <label for="duda">Comentario, duda o inquietud:</label>
                            <textarea name="comentario" form="form" class="form-control" id="duda" placeholder="Comentario, duda o inquietud..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop