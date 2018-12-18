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
$router->get('index','OAuth2Controller@index');
$router->get('authorize','OAuth2Controller@auth');
$router->post('authorize','OAuth2Controller@auth');
$router->post('access_token','OAuth2Controller@access_token');
$router->get('qrcode/{clientId}','OAuth2Controller@qrcode');
$router->get('qrcode/login/{clientId}','OAuth2Controller@qrcodeLogin');

$router->get('/test/{name}', 'ExampleController@test');
$router->post('/test/{name}', 'ExampleController@test');