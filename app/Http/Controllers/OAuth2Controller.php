<?php

namespace App\Http\Controllers;

use App\Helper\ApiHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\OAuth2\Entities\UserEntity;
use App\OAuth2\Repositories\AccessTokenRepository;
use App\OAuth2\Repositories\AuthCodeRepository;
use App\OAuth2\Repositories\ClientRepository;
use App\OAuth2\Repositories\RefreshTokenRepository;
use App\OAuth2\Repositories\ScopeRepository;
use App\Service\OAuth2Service;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OAuth2Controller extends Controller
{
    use Output;
    /**
     * @var ApiHelper
     */
    private $_apiHelper;
    /**
     * @var Request
     */
    private $_request;
    /**
     * @var ServerRequestInterface
     */
    private $_psrRequest;
    /**
     * @var ResponseInterface
     */
    private $_psrResponse;
    /**
     * @var AuthorizationServer
     */
    private $_authorizationServer;
    /**
     * @var AccessTokenRepository
     */
    private $_accessTokenRepository;
    /**
     * @var AuthCodeRepository
     */
    private $_authCodeRepository;
    /**
     * @var ClientRepository
     */
    private $_clientRepository;
    /**
     * @var RefreshTokenRepository
     */
    private $_refreshTokenRepository;
    /**
     * @var ScopeRepository
     */
    private $_scopeRepository;
    /**
     * @var OAuth2Service
     */
    private $_oauth2;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiHelper $apiHelper, Request $request, ServerRequestInterface $psrRequest, ResponseInterface $psrResponse, ClientRepository $clientRepository, AccessTokenRepository $accessTokenRepository, ScopeRepository $scopeRepository, AuthCodeRepository $authCodeRepository, RefreshTokenRepository $refreshTokenRepository, OAuth2Service $oauth2)
    {
        $privateKey = env('APP_PRIVATE_KEY');
        $encryptionKey = env('APP_KEY');
        $this->_apiHelper = $apiHelper;
        $this->_request = $request;
        $this->_psrRequest = $psrRequest;
        $this->_psrResponse = $psrResponse;
        $this->_authorizationServer = new AuthorizationServer($clientRepository, $accessTokenRepository, $scopeRepository, $privateKey, $encryptionKey);
        $this->_accessTokenRepository = $accessTokenRepository;
        $this->_authCodeRepository = $authCodeRepository;
        $this->_clientRepository = $clientRepository;
        $this->_refreshTokenRepository = $refreshTokenRepository;
        $this->_scopeRepository = $scopeRepository;
        $this->_oauth2 = $oauth2;
    }

    public function index()
    {
        // $query = $this->_psrRequest->getQueryParams();
        // $query = json_decode(json_encode())
        $form = new \stdClass;
        $code = $this->_request->get('code') ?: '';

        $res = $this->_apiHelper->httpPost('/access_token', ['grant_type' => 'authorization_code', 'code' => $code, 'client_id' => 'myawesomeapp', 'client_secret' => 'abc123', 'redirect_uri' => url('/index')]);
        $data = $this->_apiHelper->convert($res);

        return $this->success($data);
    }
    //
    public function auth($ability, $arguments = [])
    {
        // 使用令牌码
        $this->_authorizationServer->enableGrantType(
            new AuthCodeGrant(
                $this->_authCodeRepository,
                $this->_refreshTokenRepository,
                new \DateInterval('PT10M')
            ),
            new \DateInterval('PT1H')
        );

        // dump($this->_authorizationServer);
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_psrRequest);
        
        $form = new \stdClass;
        $form->socketServerUri = (is_https() ? 'wss' : 'ws') . '://' . env('SOCKET_SERVER_HOST') . ':' . env('SOCKET_SERVER_PORT');
        $form->sessionUser = empty($_SESSION['user']) ? null : json_decode(json_encode($_SESSION['user']));

        return view('oauth2.authorize', ['form' => $form]);
    }

    public function authPost($ability, $arguments = [])
    {
        // 使用令牌码
        $this->_authorizationServer->enableGrantType(
            new AuthCodeGrant(
                $this->_authCodeRepository,
                $this->_refreshTokenRepository,
                new \DateInterval('PT10M')
            ),
            new \DateInterval('PT1H')
        );

        $form = new \stdClass;
        $form->username = $this->_request->input('username') ?: '';
        $form->password = $this->_request->input('password') ?: '';
        $form->socketServerUri = (is_https() ? 'wss' : 'ws') . '://' . env('SOCKET_SERVER_HOST') . ':' . env('SOCKET_SERVER_PORT');

        list($success, $user) = $this->_oauth2->login($form);
        if($success) {
            // dump($this->_authorizationServer);
            $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_psrRequest);
    
            // Once the user has logged in set the user on the AuthorizationRequest
            $authRequest->setUser($user);
    
            // Once the user has approved or denied the client update the status
            // (true = approved, false = denied)
            $authRequest->setAuthorizationApproved(true);
    
            // Return the HTTP redirect response
            return $this->_authorizationServer->completeAuthorizationRequest($authRequest, $this->_psrResponse);
        } else {
            $form->sessionUser = null;
            return view('oauth2.authorize', ['form' => $form]);
        }
    }

    public function access_token()
    {
        // 使用令牌码
        $this->_authorizationServer->enableGrantType(
            new AuthCodeGrant(
                $this->_authCodeRepository,
                $this->_refreshTokenRepository,
                new \DateInterval('PT10M')
            ),
            new \DateInterval('PT1H')
        );

        return $this->_authorizationServer->respondToAccessTokenRequest($this->_psrRequest, $this->_psrResponse);
    }

    public function qrcode($clientId, $size = 500)
    {
        $barcode = url("qrcode/login/{$clientId}");
        $response = QrCode::format('png')->margin(1)->errorCorrection('H')->size($size)->encoding('UTF-8')->generate($barcode);
        $cachetime = 2592000; // 增加图片前端缓存30天

        return response($response, 200, ['Content-Length' => strlen($response), 'Content-Type' => 'image/png', 'Cache-Control' => "max-age=$cachetime"]);
    }

    public function qrcodeLogin($clientId)
    {
        
    }
}
