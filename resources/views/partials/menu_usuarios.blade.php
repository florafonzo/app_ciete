

<div class="col-md-4 col-xs-12 opciones_part">
    <div class="row">
        <div class="col-md-6 col-xs-12-6 col-md-offset-3">
            <img class="img-responsive" src="{{URL::to('/')}}/images/images_perfil/{{$foto}}">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12 menu_part">
            <div class="navbar-header" style="color: white;">
               {{--<strong> Opciones </strong>--}}
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-bar-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="nav-bar-2">
                <ul class="nav nav-pills nav-stacked">
                    @if(Entrust::can('ver_perfil_part'))
                        <li class="menu_usuarios @if(Request::is('participante*') and (!(Request::is('participante/cursos*')) and !(Request::is('participante/webinars*')))) active @endif">
                            <a class="menu_usu" href="{{URL::to('/participante/perfil')}}"> Ver perfil </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_perfil_prof'))
                        <li class="menu_usuarios @if(Request::is('profesor*') and (!(Request::is('profesor/cursos*')) and !(Request::is('profesor/webinars*')))) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/profesor/perfil')}}"> Ver perfil </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_usuarios'))
                        <li class=" menu_usuarios @if(Request::is('usuarios*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/usuarios')}}"> Usuarios </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_lista_cursos'))
                        <li class="menu_usuarios @if(Request::is('cursos') or (!(Request::is('cursos/desactivados*')) and Request::is('cursos/*'))) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/cursos')}}"> Cursos </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_lista_cursos'))
                        <li class="menu_usuarios @if(Request::is('cursos/desactivados*')) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/cursos/desactivados')}}"> Cursos desactivados </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_roles'))
                        <li class="menu_usuarios @if(Request::is('roles*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/roles')}}"> Roles </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_webinars'))
                        <li class="menu_usuarios @if(Request::is('webinars') or (!(Request::is('webinars/desactivados*')) and Request::is('webinars/*'))) active @endif">
                            <a class="menu_usu" href="{{URL::to('/webinars')}}"> Webinars </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_webinars'))
                        <li class="menu_usuarios @if(Request::is('webinars/desactivados*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/webinars/desactivados')}}"> Webinars desactivados </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_cursos_part'))
                        <li class="menu_usuarios @if(Request::is('participante/cursos*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/participante/cursos')}}"> Cursos inscritos </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_cursos_part'))
                        <li class="menu_usuarios @if(Request::is('participante/webinars*')) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/participante/webinars')}}"> Webinars inscritos </a>
                        </li>
                    @endif
                    @if(Entrust::can('activar_preinscripcion'))
                        <li class="menu_usuarios @if(Request::is('preinscripcion')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/preinscripcion/cursos/procesar')}}"> Preinscripciones Cursos </a>
                        </li>
                    @endif
                    @if(Entrust::can('activar_preinscripcion'))
                        <li class="menu_usuarios @if(Request::is('preinscripcion')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/preinscripcion/webinars/procesar')}}"> Preinscripciones Webinars </a>
                        </li>
                    @endif
                    @if(Entrust::can('activar_preinscripcion'))
                        <li class="menu_usuarios @if(Request::is('')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/')}}/inscripcion/procesar"> Gestión Inscripciones </a>
                        </li>
                    @endif
                    {{--@if(Entrust::can('activar_preinscripcion'))--}}
                        {{--<li class="menu_usuarios @if(Request::is('')) active @endif">--}}
                            {{--<a class="menu_usu" href="{{URL::to('/')}}">  Gestión Inscripciones Webinars </a>--}}
                        {{--</li>--}}
                    {{--@endif--}}
                    @if(Entrust::can('ver_cursos_profe'))
                        <li class="menu_usuarios @if(Request::is('profesor/cursos*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/profesor/cursos')}}"> Cursos dictados</a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_cursos_profe'))
                        <li class="menu_usuarios @if(Request::is('profesor/webinars*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/profesor/webinars')}}"> Webinars dictados</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
