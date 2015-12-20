<p>
  Has recibido un nuevo mensaje desde tu formulario de contacto.
</p>
<p>
  Aquí los detalles:
</p>
<ul>
  <li>Nombre: <strong>{{ $nombre }}</strong></li>
  <li>Apellido: <strong>{{ $apellido }}</strong></li>
  <li>Lugar: <strong>{{ $lugar }}</strong></li>
  <li>Correo: <strong>{{ $correo }}</strong></li>
  <li>Comentario: <strong>{{ $comentario }}</strong></li>
</ul>
<hr>
<p>
  @foreach ($messageLines as $messageLine)
    {{ $messageLine }}<br>
  @endforeach
</p>
<hr>
