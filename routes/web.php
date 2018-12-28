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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->group([], function() use ($router) {
    $router->get('/','Portal\PortalController@index');
    $router->get('index','Portal\PortalController@index');
    $router->get('authorize','OAuth2\AuthorizeController@auth');
    $router->post('authorize','OAuth2\AuthorizeController@auth');
    $router->post('access_token','OAuth2\AccessTokenController@access_token');
    $router->post('token','OAuth2\AccessTokenController@access_token');
    $router->get('qrcode/generate/{clientId}/{socketClientId}','OAuth2\AssistController@qrcode');
    $router->get('qrcode/authorize/{encrypt}','OAuth2\AssistController@qrcodeAuthorize');
    $router->post('qrcode/authorize/{encrypt}','OAuth2\AssistController@qrcodeAuthorize');
    $router->get('qrcode/callback/{thirdId}/{encrypt}','OAuth2\AssistController@qrcodeCallback');
    $router->post('qrcode/callback/{thirdId}/{encrypt}','OAuth2\AssistController@qrcodeCallback');
    $router->get('third/weixin/authorize','Third\WeixinController@auth');
    $router->get('third/weixin/callback','Third\WeixinController@callback');

    // 需要访问令牌
    $router->group(['middleware' => 'access'], function() use ($router) {
        $router->get('resource', 'OAuth2\ResourceController@resource');
        $router->post('resource', 'OAuth2\ResourceController@resource');
    });
});
// for test
$router->get('/test/{name}', 'ExampleController@test');
$router->post('/test/{name}', 'ExampleController@test');