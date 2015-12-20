<p>
  You have received a new message from your website contact form.
</p>
<p>
  Here are the details:
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
