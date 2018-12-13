<?php

namespace App\Http\Controllers\OAuth2;

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

class TokenController extends Controller
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
        $this->_authorizationServer = new AuthorizationServer(
            $clientRepository, 
            $accessTokenRepository, 
            $scopeRepository, 
            $privateKey, 
            $encryptionKey
        );
        $this->_accessTokenRepository = $accessTokenRepository;
        $this->_authCodeRepository = $authCodeRepository;
        $this->_clientRepository = $clientRepository;
        $this->_refreshTokenRepository = $refreshTokenRepository;
        $this->_scopeRepository = $scopeRepository;
        
        $this->_authorizationServer->enableGrantType(
            new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
            new \DateInterval('PT1H') // access tokens will expire after 1 hour
        );
    }

    public function token()
    {

    }
}
