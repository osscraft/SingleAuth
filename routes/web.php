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
$router->get('authorize','OAuth2Controller@auth');
$router->post('authorize','OAuth2Controller@authPost');
$router->post('access_token','OAuth2Controller@access_token');
// $router->group(['prefix' => 'api'], function () use ($router) {
//     // $router->get('hello',['middleware'=>'token','uses'=>'AdminController@index']);
//     // $router->get('login','AdminController@login');
// });

$router->get('/test/{name}', 'ExampleController@test');
$router->post('/test/{name}', 'ExampleController@test');