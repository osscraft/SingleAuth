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
$router->get('authorize','OAuth2Controller@authorize');
$router->get('token','OAuth2Controller@token');
// $router->group(['prefix' => 'api'], function () use ($router) {
//     // $router->get('hello',['middleware'=>'token','uses'=>'AdminController@index']);
//     // $router->get('login','AdminController@login');
// });
