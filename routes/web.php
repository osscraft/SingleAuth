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
$router->get('qrcode/generate/{clientId}/{socketClientId}','AssistController@qrcode');
$router->get('qrcode/authorize/{encrypt}','AssistController@qrcodeAuthorize');
$router->post('qrcode/authorize/{encrypt}','AssistController@qrcodeAuthorize');
$router->get('qrcode/callback/{thirdId}/{encrypt}','AssistController@qrcodeCallback');
$router->post('qrcode/callback/{thirdId}/{encrypt}','AssistController@qrcodeCallback');
$router->get('third/weixin/authorize','WeixinController@auth');
$router->get('third/weixin/callback','WeixinController@callback');

$router->get('/test/{name}', 'ExampleController@test');
$router->post('/test/{name}', 'ExampleController@test');