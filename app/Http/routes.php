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

//---------- Información CIETE y Créditos --------------//
Route::get('Misión-y-Visión','InformacionController@mision_vision');
Route::get('Estructura','InformacionController@estructura');
Route::get('Servicios','InformacionController@servicios');
Route::get('Equipo','InformacionController@equipo');	
Route::get('Créditos','InformacionController@creditos');

//* ---------- Información CIETE y Créditos --------------//

/*Route::get('Participante','InformacionController@participante');
Route::get('Profesor','InformacionController@profesor');*/
//Route::get('/usuarios','UsuariosController@index');

Route::resource('/usuarios','UsuariosController');

Route::resource('/cursos','CursosController');

Route::resource('/roles','RolesController');

Route::resource('/webinars','WebinarsController');

//Route::get('/usuarios','UsuariosController@index');
//Route::get('twit', function()
//{
//    return Twitter::getUserTimeline(['screen_name' => 'cieteula', 'count' => 20, 'format' => 'json']);
//});

//Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');

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

//Ruta dirección usuarios
Route::get('/direccion', function(){

    $id = Input::get('id_est');
    $ciudades = App\Models\Ciudad::where('id_estado', '=', '1' )->get();

    return Response::json($ciudades);
});