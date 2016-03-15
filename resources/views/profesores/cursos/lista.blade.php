<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        body{
            font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;
        }
        .titulo_{
            width: 100%;
            text-align: center;
        }
        .todo_{
            width: 100%;
            text-align: center;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            width: inherit;
        }

        .imagen_{
            width: 100px;
            height: auto;
        }
        .nombres{
            background-color: lightslategrey;
        }
    </style>
</head>
<body>
    <div class="titulo_">
        <img class="imagen_" src="{{URL::to('/')}}/images/ciete_logo.jpg">
        <h3>
            Participantes del curso: {{$curso->nombre}} <br/> Sección - {{$seccion}}
        </h3>
    </div>
    <div class="">
        <table class="todo_">
            <thead class="nombres">
            <tr>
                <th class="columna">Nombre</th>
                <th class="columna">Apellido</th>
                <th class="columna">Documento de identidad</th>
            </tr>
            </thead>
            @if($participantes != null)
                <tbody class="todo_">
                @foreach($participantes as $participante)
                    <tr>
                        <td>{{ $participante[0]->nombre }}</td>
                        <td>{{ $participante[0]->apellido }}</td>
                        <td>{{ $participante[0]->documento_identidad }}</td>
                        <td>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            @else
                <td>No existen participantes</td>
            @endif
        </table>
    </div>
</body>
</html>
