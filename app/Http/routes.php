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

//Rutas Loggin y recuperación de contraseñas
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


Route::group([
    'middleware' => 'auth'],
    function(){
    //Rutas manejo de usuarios//
    Route::resource('/usuarios','UsuariosController');

    //Rutas manejo de cursos
    Route::get('cursos-desactivados', 'CursosController@indexDesactivados');
    Route::get('cursos-desactivados/activar/{id}', 'CursosController@activar');
    Route::get('cursos/{id}/participantes', 'CursosController@cursoParticipantes');
    Route::get('cursos/{id}/participantes/agregar', 'CursosController@cursoParticipantesAgregar');
    Route::get('cursos/{id_curso}/participantes/{id_part}/agregar', 'CursosController@cursoParticipantesGuardar');
    Route::delete('cursos/{id_curso}/participantes/{id_part}/eliminar', 'CursosController@cursoParticipantesEliminar');
    Route::get('cursos/{id}/profesores', 'CursosController@cursoProfesores');
    Route::get('cursos/{id}/profesores/agregar', 'CursosController@cursoProfesoresAgregar');
    Route::get('cursos/{id_curso}/profesores/{id_part}/agregar', 'CursosController@cursoProfesoresGuardar');
    Route::delete('cursos/{id_curso}/profesores/{id_part}/eliminar', 'CursosController@cursoProfesoresEliminar');
    Route::resource('/cursos','CursosController');

    //Rutas manejo de roles
    Route::resource('/roles','RolesController');

    //Rutas manejo de webinars
    Route::get('webinars-desactivados', 'WebinarsController@indexDesactivados');
    Route::get('webinars-desactivados/activar/{id}', 'WebinarsController@activar');
    Route::get('webinars/{id}/participantes', 'WebinarsController@webinarParticipantes');
    Route::get('webinars/{id}/participantes/agregar', 'WebinarsController@webinarParticipantesAgregar');
    Route::get('webinars/{id_webinar}/participantes/{id_part}/agregar', 'WebinarsController@webinarParticipantesGuardar');
    Route::delete('webinars/{id_webinar}/participantes/{id_part}/eliminar', 'WebinarsController@webinarParticipantesEliminar');
    Route::get('webinars/{id}/profesores', 'WebinarsController@webinarProfesores');
    Route::get('webinars/{id}/profesores/agregar', 'WebinarsController@webinarProfesoresAgregar');
    Route::get('webinars/{id_webinar}/profesores/{id_part}/agregar', 'WebinarsController@webinarProfesoresGuardar');
    Route::delete('webinars/{id_webinar}/profesores/{id_part}/eliminar', 'WebinarsController@webinarProfesoresEliminar');
    
    Route::resource('/webinars','WebinarsController');

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
    Route::get('/participante/perfil','ParticipantesController@verPerfil');
    Route::get('/participante/perfil/{id}/editar','ParticipantesController@editarPerfil');
    Route::get('participante/perfil/imagen','ParticipantesController@cambiarImagen');
    Route::post('participante/perfil/procesar','ParticipantesController@procesarImagen');
    Route::get('/participante/cursos','ParticipantesController@verCursos');
    Route::get('/participante/cursos/{id}/notas','ParticipantesController@verNotasCurso');
    Route::get('/participante/webinars','ParticipantesController@verWebinars');
    Route::get('/participante/webinars/{id}/notas','ParticipantesController@verNotasCurso');
    Route::resource('/participante','ParticipantesController');
});

//Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');