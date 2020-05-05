<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});

$router->group(['prefix' => 'api/'], function ($app) {
    $app->post('login', 'UsersController@authenticate');
    $app->post('register','UsersController@register');
    $app->post('note','NotesController@create');
    $app->get('note','NotesController@index');
    $app->get('note/{id}','NotesController@note');
    $app->put('note','NotesController@update');
    $app->delete('note/{id}','NotesController@delete');
});

$router->get('/{route:.*}/', function ()  {
    return view('app');
});

