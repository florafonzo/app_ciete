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

//Rutas manejo de usuarios//
Route::resource('/usuarios','UsuariosController');

//Rutas manejo de cursos
Route::get('cursos-desactivados', 'CursosController@indexDesactivados');
Route::get('cursos-desactivados/activar/{id}', 'CursosController@activar');
Route::resource('/cursos','CursosController');

//Rutas manejo de roles
Route::resource('/roles','RolesController');

//Rutas manejo de webinars
Route::resource('/webinars','WebinarsController');

//Rutas Loggin y recuperación de contraseñas
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

//Rutas de correo
Route::post('/password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('/password/reset', 'Auth\PasswordController@postReset');

//Ruta de contacto
Route::get('Contacto','InformacionController@getcontacto');
Route::post('Contacto','InformacionController@postContacto');

//Ruta dirección participantes
Route::get('/direccion', function(){

    $id = Input::get('id_est');
    $ciudades = App\Models\Ciudad::where('id_estado', '=', '1' )->get();

    return Response::json($ciudades);
});

//Rutas participante
Route::get('/participante/perfil','ParticipantesController@verPerfil');
Route::get('/participante/perfil/editar','ParticipantesController@editarPerfil');
//Route::get('/participante','ParticipantesController@update');
Route::resource('/participante','ParticipantesController');

//Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');