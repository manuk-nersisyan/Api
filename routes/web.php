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

$router->group(['prefix' => 'api'], function($router) {
    $router->group(['prefix' => 'my-team'], function($router) {
        $router->get('/', 'TeamController@index');
        $router->get('/{id}', 'TeamController@show');
        $router->post('/create', 'TeamController@store');
        $router->delete('/delete/{id}', 'TeamController@destroy');
        $router->put('/update/{id}', 'TeamController@update');
    });
    $router->group(['prefix' => 'my-team-users'], function($router) {
          $router->get('/', 'TeamUsersController@index');
          $router->get('/{id}', 'TeamUsersController@show');
          $router->post('/create/{id}', 'TeamUsersController@store');
          $router->delete('/delete/{id}', 'TeamUsersController@destroy');
          $router->put('/update/{id}', 'TeamUsersController@update');
    });
});