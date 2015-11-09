<header>
    <div class="container">
        <div class="row derecha">
            <span>
                {{--@if( Auth::check() )--}}
                    {{--@$request->user()--}}
                {{--@else--}}
                    {{--Usted no se ha identificado--}}
                    {{--<a href="{{URL::to('auth/login')}}" class="btn btn-primary"> Entrar </a>--}}
                {{--@endif--}}
                @if (Auth::guest())
                    Usted no se ha identificado
                    <a href="{{URL::to('auth/login')}}" class="btn btn-primary"> Entrar </a>
                @else
                    <li class="dropdown logeado">
                        <a href="#" class="dropdown-toggle nombre_salir" data-toggle="dropdown" role="button" aria-expanded="false"><span class="caret"></span>  {{ Auth::user()->nombre }} {{ Auth::user()->apellido }} </a>
                        <ul class="dropdown-menu derecha_li" role="menu">
                            <li class=""><a href="{{ url('/auth/logout') }}"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Cerrar sesión</a></li>
                        </ul>
                    </li>
                @endif
            </span>
        </div>
        <div class="row centro">
            <div class="logo_princ">
                <a href="/"><img class="img-responsive" src="{{URL::to('/')}}/images/ciete_logo.jpg"></a>
            </div>
        </div>
    </div>
</header>