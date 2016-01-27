

<div class="col-md-4 col-sm-4 opciones_part">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-md-offset-3">
            <img src="{{URL::to('/')}}/images/foto_participante.png">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 menu_part">
            <ul class="nav nav-pills nav-stacked">
                @if(Entrust::can('ver_usuarios'))
                    <li class=" menu_usuarios @if(Request::is('usuarios*')) active @endif">
                        <a href="{{URL::to('/usuarios')}}"> Usuarios </a>
                    </li>
                @endif
                @if(Entrust::can('ver_lista_cursos'))
                    <li class="menu_usuarios @if(Request::is('cursos*')) active @endif">
                        <a style="text-decoration:none;" href="{{URL::to('/cursos')}}"> Lista de cursos </a>
                    </li>
                @endif
                @if(Entrust::can('ver_roles'))
                    <li class="menu_usuarios @if(Request::is('roles*')) active @endif">
                        <a style="text-decoration:none;" href="{{URL::to('/roles')}}"> Roles </a>
                    </li>
                @endif
                @if(Entrust::can('ver_webinars'))
                    <li class="menu_usuarios @if(Request::is('webinars*')) active @endif">
                        <a style="text-decoration:none;" href="{{URL::to('/webinars')}}"> Webinars </a>
                    </li>
                @endif
                    {{--<li class="menu_usuarios @if(Request::is('carrusel*')) active @endif">--}}
                        {{--<a href="#"> Carrusel </a>--}}
                    {{--</li>--}}

                @if(Entrust::can('ver_perfil'))
                    <li>
                        Ver Pefil
                    </li>
                @endif
                @if(Entrust::can('ver_cursos_profe'))
                    <li>
                        Cursos
                    </li>
                @endif
                @if(Entrust::can('ver_notas_profe'))
                    <li>
                        Notas
                    </li>
                @endif
                @if(Entrust::can('ver_lista'))
                    <li>
                        Listas de participantes
                    </li>
                @endif
                @if(Entrust::can('ver_perfil'))
                    <li>
                        <a href="#"> Ver Perfil </a>
                    </li>
                @endif
                @if(Entrust::can('ver_historial'))
                    <li>
                        <a href="#"> Historial de cursos </a>
                    </li>
                @endif
                @if(Entrust::can('ver_certificados'))
                    <li>
                        <a href="#"> Obtener Certificado </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
