<nav class="navbar">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active menu_borde">
                    <a class="lista-menu" href="/">Inicio</a>
                </li>
                <li class="dropdown menu_borde">
                    <a class="lista-menu" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cursos Disponibles <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Presenciales</a></li>
                        <li><a href="#">A Distancia</a></li>
                    </ul>
                </li>
                <li class="menu_borde">
                    <a class="lista-menu" href="#">¿Cómo inscribirse?</a>
                </li>
                <li class="dropdown">
                    <a class="lista-menu" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Información del Centro <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/Misión-y-Visión">¿Quiénes somos?</a></li>
                        <li><a href="/Estructura">Estructura</a></li>
                        <li><a href="/Servicios">Servicios</a></li>
                        <!--<li role="separator" class="divider"></li>-->
                        <li><a href="/Equipo">Equipo</a></li>
                        <li><a href="/Contacto">Contacto</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="col-md-4 col-xs-12 text-center redes">
            <ul class="list-inline">
                <li><a href="https://www.facebook.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-facebook.png" alt=""/></a></li>
                <li><a href="https://twitter.com/cieteula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-twitter.png" alt=""/></a></li>
                <li><a href="https://www.youtube.com/channel/UCY9f2COL913LKZoxeFcoQPA"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-youtube.png" alt=""/></a></li>
                <li><a href="http://instagram.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-instagram.png" alt=""/></a></li>
                <li><a href="https://plus.google.com/u/0/+CIETEULA/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-googleplus.png" alt=""/></a></li>
                <li><a href="http://cieteula.tumblr.com/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-tumblr.png" alt=""/></a></li>
                <li><a href="https://www.pinterest.com/raymarq/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-pinterest.png" alt=""/></a></li>
            </ul>
        </div>
    </div>

</nav>

{{--<nav class="clearfix row" >--}}
    {{--<a href="" id="menu-button"><i class="fa fa-bars"></i></a>--}}
    {{--<ul class="menu dropdown col-md-8 list-inline text-center">--}}
        {{--<li>--}}
            {{--<a class="lista-menu" href="/">Inicio</a>--}}
        {{--</li>--}}
        {{--<li class="dropdown-submenu lista-menu">--}}
            {{--Cursos Disponibles--}}
            {{--<ul class="dropdown-menu" role="menu">--}}
                {{--<li><a tabindex="-1" href="#">Presenciales</a></li>--}}
                {{--<li><a tabindex="-1" href="#">A distancia</a></li>--}}
            {{--</ul>--}}
        {{--</li>--}}
        {{--<li>--}}
            {{--<a class="lista-menu" id="" >--}}
                {{--¿Cómo inscribirse?--}}
            {{--</a>--}}
        {{--</li>--}}
        {{--<li class="dropdown-submenu lista-menu">--}}
            {{--Información del Centro--}}
            {{--<ul class="dropdown-menu" role="menu">--}}
                {{--<li><a tabindex="-1" href="/Misión-y-Visión">¿Quiénes somos?</a></li>--}}
                {{--<li><a tabindex="-1" href="/Estructura">Estructura</a></li>--}}
                {{--<li><a tabindex="-1" href="/Servicios">Servicios</a></li>--}}
                {{--<li><a tabindex="-1" href="/Equipo">Equipo</a></li>--}}
                {{--<li><a tabindex="-1" href="Contacto">Contacto</a></li>--}}
            {{--</ul>--}}
        {{--</li>--}}
    {{--</ul>--}}
    {{--<div class="col-md-4 col-xs-12 text-center redes">--}}
        {{--<ul class="list-inline">--}}
            {{--<li><a href="https://www.facebook.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-facebook.png" alt=""/></a></li>--}}
            {{--<li><a href="https://twitter.com/cieteula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-twitter.png" alt=""/></a></li>--}}
            {{--<li><a href="https://www.youtube.com/channel/UCY9f2COL913LKZoxeFcoQPA"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-youtube.png" alt=""/></a></li>--}}
            {{--<li><a href="http://instagram.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-instagram.png" alt=""/></a></li>--}}
            {{--<li><a href="https://plus.google.com/u/0/+CIETEULA/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-googleplus.png" alt=""/></a></li>--}}
            {{--<li><a href="http://cieteula.tumblr.com/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-tumblr.png" alt=""/></a></li>--}}
            {{--<li><a href="https://www.pinterest.com/raymarq/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-pinterest.png" alt=""/></a></li>--}}
        {{--</ul>--}}
    {{--</div>--}}
{{--</nav>--}}