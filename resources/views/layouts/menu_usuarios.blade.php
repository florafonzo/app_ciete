

    <div class="col-md-4 col-sm-4 opciones_part">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-md-offset-3">
                <img src="{{URL::to('/')}}/images/foto_participante.png">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 menu_part">
                <ul class="nav nav-pills nav-stacked">
                    @if(Auth::user()->hasRole('admin'))
                        <li class=" menu_usuarios">
                            <a href="{{URL::to('/usuarios')}}"> Usuarios </a>
                        </li>
                        <li class="menu_usuarios">
                            <a style="text-decoration:none;" href="{{URL::to('/cursos')}}"> Lista de cursos </a>
                        </li>
                        <li class="menu_usuarios">
                            <a href="#"> Carrusel </a>
                        </li>

                    @elseif(Auth::user()->hasRole('profesor'))
                        <li>
                            Ver Pefil
                        </li>
                        <li>
                            Cursos
                        </li>
                        <li>
                            Notas
                        </li>
                        <li>
                            Listas de participantes
                        </li>
                    @elseif(Auth::user()->hasRole('participante'))
                        <li>
                            <a href="#"> Ver Perfil </a>
                        </li>
                        <li>
                            <a href="#"> Historial de cursos </a>
                        </li>
                        <li>
                            <a href="#"> Obtener Certificado </a>
                        </li>
                    @else
                        <div class="col-md-12 col-sm-12 bienvenida">
                            <h3>
                                No tiene los perrmisos apropiados
                            </h3>
                        </div>
                    @endif
                </ul>
            </div>
        </div>
    </div>
