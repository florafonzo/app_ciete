<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'InicioController@index');
Route::get('/descripcion/curso/{id}', 'InicioController@descCurso');
Route::get('/descripcion/webinar/{id}', 'InicioController@descWebinar');



//Rutas Información CIETE y Créditos
Route::get('Misión-y-Visión','InformacionController@mision_vision');
Route::get('Estructura','InformacionController@estructura');
Route::get('Servicios','InformacionController@servicios');
Route::get('Equipo','InformacionController@equipo');	
Route::get('Créditos','InformacionController@creditos');

//Rutas de correo
Route::post('/password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('/password/reset', 'Auth\PasswordController@postReset');

//Ruta de contacto
Route::get('Contacto','InformacionController@getcontacto');
Route::post('Contacto','InformacionController@postContacto');

//Ruta prenscripcion
Route::get('/preinscripcion/cursos', 'PreinscripcionController@mostrarPreinscripcionCurso');
Route::get('/preinscripcion/webinars', 'PreinscripcionController@mostrarPreinscripcionWebinar');
Route::post('preinscripcion/cursos','PreinscripcionController@storePreinscripcionCurso');
Route::post('preinscripcion/webinars','PreinscripcionController@storePreinscripcionWebinar');

//Rutas Loggin y recuperación de contraseñas
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


Route::group([
    'middleware' => 'auth'],
    function(){
    //Rutas manejo de usuarios//
    Route::get('usuarios/buscar', [
        'as' => 'usuarios.buscar', 'uses' => 'UsuariosController@buscar'
    ]);
    Route::resource('usuarios','UsuariosController');

    //Rutas manejo de cursos
    Route::get('cursos/buscar', [
        'as' => 'cursos.buscar', 'uses' => 'CursosController@buscar'
    ]);
    Route::get('cursos/desactivados', 'CursosController@indexDesactivados');
    Route::get('cursos/desactivados/activar/{id}', 'CursosController@activar');
    Route::get('cursos/desactivados/buscar', [
        'as' => 'cursos/desactivados.buscar', 'uses' => 'CursosController@buscar2'
    ]);

        Route::get('cursos/imagen','CursosController@cambiarImagen');
        Route::post('cursos/procesar','CursosController@procesarImagen');

    Route::get('cursos/imagen/{id}','CursosController@cambiarImagen1');
    Route::post('cursos/procesar/{id}','CursosController@procesarImagen1');

    Route::get('cursos/{id}/secciones', 'CursosController@seccionesMoodle');
    Route::get('cursos/{id}/secciones/{seccion}/lista', 'CursosController@listaMoodle');

    Route::get('cursos/{id}/secciones/participantes', 'CursosController@cursoSeccionesParts');
    Route::get('cursos/{id_curso}/secciones/{seccion}/participantes', 'CursosController@cursoParticipantes');
        Route::get('cursos/{id_curso}/secciones/{seccion}/participantes/buscar', [
            'as' => 'cursos.participantes.buscar', 'uses' => 'CursosController@buscarParticipante'
        ]);
    Route::get('cursos/{id_curso}/secciones/{seccion}/participantes/agregar', 'CursosController@cursoParticipantesAgregar');
        Route::get('cursos/{id_curso}/secciones/{seccion}/participantes/agregar/buscar', [
            'as' => 'cursos.participantes.buscar', 'uses' => 'CursosController@buscarParticipanteAgregar'
        ]);
    Route::get('cursos/{id_curso}/secciones/{seccion}/participantes/{id_part}/agregar', 'CursosController@cursoParticipantesGuardar');
    Route::delete('cursos/{id_curso}/secciones/{seccion}/participantes/{id_part}/eliminar', 'CursosController@cursoParticipantesEliminar');
    Route::get('cursos/{id}/secciones/profesores', 'CursosController@cursoSeccionesProfes');
    Route::get('cursos/{id}/secciones/{seccion}/profesores', 'CursosController@cursoProfesores');
        Route::get('cursos/{id_curso}/secciones/{seccion}/profesores/buscar', [
            'as' => 'cursos.profesores.buscar', 'uses' => 'CursosController@buscarProfesor'
        ]);
    Route::get('cursos/{id}/secciones/{seccion}/profesores/agregar', 'CursosController@cursoProfesoresAgregar');
        Route::get('cursos/{id_curso}/secciones/{seccion}/profesores/agregar/buscar', [
            'as' => 'cursos.participantes.buscar', 'uses' => 'CursosController@buscarProfesorAgregar'
        ]);
    Route::get('cursos/{id_curso}/secciones/{seccion}/profesores/{id_part}/agregar', 'CursosController@cursoProfesoresGuardar');
    Route::delete('cursos/{id_curso}/secciones/{seccion}/profesores/{id_part}/eliminar', 'CursosController@cursoProfesoresEliminar');
    Route::resource('cursos','CursosController',['only' => ['index','create','store','update','edit', 'destroy']]);

    //Rutas manejo de roles
    Route::get('roles/buscar', [
        'as' => 'roles.buscar', 'uses' => 'RolesController@buscar'
    ]);
    Route::resource('/roles','RolesController');

    //Rutas manejo de webinars
    Route::get('webinars/buscar', [
        'as' => 'webinars.buscar', 'uses' => 'WebinarsController@buscar'
    ]);
    Route::get('webinars/desactivados', 'WebinarsController@indexDesactivados');//cambiar
    Route::get('webinars/desactivados/activar/{id}', 'WebinarsController@activar');
    Route::get('webinars/desactivados/buscar', [
        'as' => 'webinars/desactivados.buscar', 'uses' => 'WebinarsController@buscar2'
    ]);

        Route::get('webinars/imagen','WebinarsController@cambiarImagen');
        Route::post('webinars/procesar','WebinarsController@procesarImagen');

        Route::get('webinars/imagen/{id}','WebinarsController@cambiarImagen1');
        Route::post('webinars/procesar/{id}','WebinarsController@procesarImagen1');

    Route::get('webinars/{id}/secciones', 'WebinarsController@seccionesMoodle');
    Route::get('webinars/{id}/secciones/{seccion}/lista', 'WebinarsController@listaMoodle');

    Route::get('webinars/{id}/secciones/participantes', 'WebinarsController@WebinarSeccionesParts');
    Route::get('webinars/{id}/secciones/{seccion}/participantes', 'WebinarsController@webinarParticipantes');
        Route::get('webinars/{id_curso}/secciones/{seccion}/participantes/buscar', [
            'as' => 'webinars.participantes.buscar', 'uses' => 'WebinarsController@buscarParticipante'
        ]);
    Route::get('webinars/{id}/secciones/{seccion}/participantes/agregar', 'WebinarsController@webinarParticipantesAgregar');
        Route::get('webinars/{id_webinar}/secciones/{seccion}/participantes/agregar/buscar', [
            'as' => 'webinars.participantes.buscar', 'uses' => 'WebinarsController@buscarParticipanteAgregar'
        ]);
    Route::get('webinars/{id_webinar}/secciones/{seccion}/participantes/{id_part}/agregar', 'WebinarsController@webinarParticipantesGuardar');
    Route::delete('webinars/{id_webinar}/secciones/{seccion}/participantes/{id_part}/eliminar', 'WebinarsController@webinarParticipantesEliminar');

    Route::get('webinars/{id}/secciones/profesores', 'WebinarsController@WebinarSeccionesProfes');
    Route::get('webinars/{id}/secciones/{seccion}/profesores', 'WebinarsController@webinarProfesores');
        Route::get('webinars/{id_curso}/secciones/{seccion}/profesores/buscar', [
            'as' => 'webinars.profesores.buscar', 'uses' => 'WebinarsController@buscarProfesor'
        ]);
    Route::get('webinars/{id}/secciones/{seccion}/profesores/agregar', 'WebinarsController@webinarProfesoresAgregar');
        Route::get('webinars/{id_webinar}/secciones/{seccion}/profesores/agregar/buscar', [
            'as' => 'webinars.profesores.buscar', 'uses' => 'WebinarsController@buscarProfesorAgregar'
        ]);
    Route::get('webinars/{id_webinar}/secciones/{seccion}/profesores/{id_part}/agregar', 'WebinarsController@webinarProfesoresGuardar');
    Route::delete('webinars/{id_webinar}secciones/{seccion}/profesores/{id_part}/eliminar', 'WebinarsController@webinarProfesoresEliminar');
    Route::resource('webinars','WebinarsController', ['only' => ['index','create','store','update','edit', 'destroy']]);

    //Ruta dirección participantes
    Route::get('/ciudad/{id}', function(){
        $url = Request::url();
        $porciones = explode("ciudad/", $url);
        $id = $porciones[1];
        $ciudades = App\Models\Ciudad::where('id_estado', '=', $id )->get();
        //$municipios = App\Models\Municipio::where('id_estado', '=', $id )->get();

        return Response::json($ciudades);
    });

    Route::get('/municipio/{id}', function(){
        $url = Request::url();
        $porciones = explode("municipio/", $url);
        $id = $porciones[1];
        $municipios = App\Models\Municipio::where('id_estado', '=', $id )->get();

        return Response::json($municipios);
    });

    Route::get('/parroquia/{id}', function(){
        $url = Request::url();
        $porciones = explode("parroquia/", $url);
        $municipio = $porciones[1];

        $parroquias = App\Models\Parroquia::where('id_municipio', '=', $municipio )->get();

        return Response::json($parroquias);
    });

    //Rutas participante
    Route::get('participante/perfil','ParticipantesController@verPerfil');
    Route::get('participante/perfil/{id}/editar','ParticipantesController@editarPerfil');
    Route::get('participante/perfil/imagen','ParticipantesController@cambiarImagen');
    Route::post('participante/perfil/procesar','ParticipantesController@procesarImagen');
//    Route::patch('participante/perfil/{id}','ParticipantesController@update');
    Route::get('participante/cursos','ParticipantesController@verCursos');
    Route::get('participante/cursos/{id}/notas','ParticipantesController@verNotasCurso');
    Route::get('participante/webinars','ParticipantesController@verWebinars');
    Route::resource('participante','ParticipantesController');


    //Rutas preinscripción
    Route::get('preinscripcion/cursos/procesar','PreinscripcionController@index');
    Route::get('preinscripcion/webinars/procesar','PreinscripcionController@indexWeb');
//    Route::post('preinscripcion/principal', 'PreinscripcionController@mostrar');
    Route::get('preinscripcion/cursos/activar/{id}',[
        'as' => 'preinscripcion.activar',
        'uses' => 'PreinscripcionController@activarPreinscripcion'
    ]);
    Route::get('preinscripcion/cursos/desactivar/{id}',[
        'as' => 'preinscripcion.desactivar',
        'uses' => 'PreinscripcionController@desactivarPreinscripcion'
    ]);
    Route::get('preinscripcion/webinars/activar/{id}',[
        'as' => 'preinscripcion.activar-web',
        'uses' => 'PreinscripcionController@activarPreinscripcionWeb'
    ]);
    Route::get('preinscripcion/webinars/desactivar/{id}',[
        'as' => 'preinscripcion.desactivar-web',
        'uses' => 'PreinscripcionController@desactivarPreinscripcionWeb'
    ]);

    //Rutas inscripcion
        Route::get('inscripcion/procesar','InscripcionController@indexCurso');
        Route::get('inscripcion/procesar/buscar', [
            'as' => 'inscripcion.buscar', 'uses' => 'InscripcionController@buscarInscripcion'
        ]);





        //Rutas profesores
    Route::get('profesor/perfil','ProfesoresController@verPerfil');
    Route::get('profesor/perfil/{id}/editar','ProfesoresController@editarPerfil');
    Route::get('profesor/perfil/imagen','ProfesoresController@cambiarImagen');
    Route::post('profesor/perfil/procesar','ProfesoresController@procesarImagen');
    Route::get('profesor/cursos','ProfesoresController@verCursos');
    Route::get('profesor/cursos/buscar', [
        'as' => 'profesor.cursos.buscar', 'uses' => 'ProfesoresController@buscarCurso'
    ]);
    Route::get('profesor/cursos/{id}/secciones','ProfesoresController@verSeccionesCurso');
    Route::get('profesor/cursos/{id}/secciones/{seccion}/participantes','ProfesoresController@verParticipantesSeccion');
        Route::get('profesor/cursos/{id}/secciones/{seccion}/participantes/buscar', [
            'as' => 'profesor.cursos.participantes.buscar', 'uses' => 'ProfesoresController@buscarParticipante'
        ]);
    Route::get('profesor/cursos/{id}/secciones/{seccion}/lista','ProfesoresController@generarLista');
    Route::get('profesor/cursos/{id}/secciones/{seccion}/participantes/{id_alumno}/notas','ProfesoresController@verNotasParticipante');
    Route::post('profesor/cursos/{id}/secciones/{seccion}/participantes/{id_alumno}/notas','ProfesoresController@store');
    Route::delete('profesor/cursos/{id}/secciones/{seccion}/participantes/{id_alumno}/notas/{id_nota}','ProfesoresController@eliminarNotasParticipante');
//    Route::get('profesor/webinars','ProfesoresController@verWebinars');
//    Route::get('profesor/webinars/{id}/secciones','ProfesoresController@verSeccionesWebinar');
    Route::get('/nota/{id}', function(){
        $url = Request::url();
        $porciones = explode("nota/", $url);
        $nota = $porciones[1];

        $nota = App\Models\Nota::where('id', '=', $nota )->get();

        return Response::json($nota);
    });

    Route::get('profesor/webinars','ProfesoresController@verWebinars');
        Route::get('profesor/webinars/buscar', [
            'as' => 'profesor.webinars.buscar', 'uses' => 'ProfesoresController@buscarWebinar'
        ]);
    Route::get('profesor/webinars/{id}/secciones','ProfesoresController@verSeccionesWebinar');
    Route::get('profesor/webinars/{id}/secciones/{seccion}/participantes','ProfesoresController@verParticipantesWebinar');
        Route::get('profesor/webinars/{id}/secciones/{seccion}/participantes/buscar', [
            'as' => 'profesor.webinars.participantes.buscar', 'uses' => 'ProfesoresController@buscarParticipanteW'
        ]);
    Route::get('profesor/webinars/{id}/secciones/{seccion}/lista','ProfesoresController@generarListaW');

    Route::resource('/profesor','ProfesoresController');
});

//Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');