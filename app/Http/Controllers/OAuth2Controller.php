<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\OAuth2\Entities\UserEntity;
use App\OAuth2\Repositories\AccessTokenRepository;
use App\OAuth2\Repositories\AuthCodeRepository;
use App\OAuth2\Repositories\ClientRepository;
use App\OAuth2\Repositories\RefreshTokenRepository;
use App\OAuth2\Repositories\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OAuth2Controller extends Controller
{
    /**
     * @var ServerRequestInterface
     */
    private $_request;
    /**
     * @var ResponseInterface
     */
    private $_response;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response, ClientRepository $clientRepository, AccessTokenRepository $accessTokenRepository, ScopeRepository $scopeRepository, AuthCodeRepository $authCodeRepository, RefreshTokenRepository $refreshTokenRepository)
    {
        $privateKey = env('APP_PRIVATE_KEY');
        $encryptionKey = env('APP_KEY');
        $this->_request = $request;
        $this->_response = $response;
        $this->_authorizationServer = new AuthorizationServer($clientRepository, $accessTokenRepository, $scopeRepository, $privateKey, $encryptionKey);
        $this->_accessTokenRepository = $accessTokenRepository;
        $this->_authCodeRepository = $authCodeRepository;
        $this->_clientRepository = $clientRepository;
        $this->_refreshTokenRepository = $refreshTokenRepository;
        $this->_scopeRepository = $scopeRepository;
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
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_request);

        // Once the user has logged in set the user on the AuthorizationRequest
        // $authRequest->setUser(new UserEntity());

        // Once the user has approved or denied the client update the status
        // (true = approved, false = denied)
        // $authRequest->setAuthorizationApproved(true);

        // Return the HTTP redirect response
        // return $this->_authorizationServer->completeAuthorizationRequest($authRequest, $this->_response);

        return view('oauth2.authorize');
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

        // dump($this->_authorizationServer);
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_request);

        // Once the user has logged in set the user on the AuthorizationRequest
        $authRequest->setUser(new UserEntity());

        // Once the user has approved or denied the client update the status
        // (true = approved, false = denied)
        $authRequest->setAuthorizationApproved(true);

        // Return the HTTP redirect response
        return $this->_authorizationServer->completeAuthorizationRequest($authRequest, $this->_response);
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

        return $this->_authorizationServer->respondToAccessTokenRequest($this->_request, $this->_response);
    }
}
