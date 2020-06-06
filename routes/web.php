<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login','AuthController@login');
$router->post('/reset','AuthController@sendResetToken');
$router->put('/reset/{token}','AuthController@verifyResetPassword');

$router->group(['middleware' => 'auth'], function() use($router){
    $router->get('/users','UserController@index');
    $router->post('/users','UserController@store');
    $router->get('/users/{id}','UserController@show');
    $router->put('/users/{id}','UserController@update');
    $router->delete('/users/{id}','UserController@destroy');
});


