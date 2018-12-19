<?php

namespace App\Service;

use App\OAuth2\Server\Entities\UserEntity;
use App\OAuth2\Server\Repositories\AccessTokenRepository;
use App\OAuth2\Server\Repositories\AuthCodeRepository;
use App\OAuth2\Server\Repositories\ClientRepository;
use App\OAuth2\Server\Repositories\RefreshTokenRepository;
use App\OAuth2\Server\Repositories\ScopeRepository;
use App\OAuth2\Server\Repositories\SessionRepository;
use App\OAuth2\Server\Repositories\UserClientRepository;
use App\OAuth2\Server\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use League\OAuth2\Server\Grant\AuthCodeGrant;

class OAuth2Service
{
    /**
     * @var Request
     */
    private $_request;
    /**
     * @var Store
     */
    private $_session;
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
     * @var SessionRepository
     */
    private $_sessionRepository;
    /**
     * @var UserClientRepository
     */
    private $_userClientRepository;
    /**
     * @var UserRepository
     */
    private $_userRepository;

    public function __construct(Request $request, ServerRequestInterface $psrRequest, ResponseInterface $psrResponse, ClientRepository $clientRepository, AccessTokenRepository $accessTokenRepository, ScopeRepository $scopeRepository, AuthCodeRepository $authCodeRepository, RefreshTokenRepository $refreshTokenRepository, SessionRepository $sessionRepository, UserClientRepository $userClientRepository, UserRepository $userRepository)
    {
        $privateKey = env('APP_PRIVATE_KEY');
        $encryptionKey = env('APP_KEY');

        $this->_request = $request;
        $this->_session = $request->session();
        $this->_psrRequest = $psrRequest;
        $this->_psrResponse = $psrResponse;
        $this->_authorizationServer = new AuthorizationServer($clientRepository, $accessTokenRepository, $scopeRepository, $privateKey, $encryptionKey);
        $this->_accessTokenRepository = $accessTokenRepository;
        $this->_authCodeRepository = $authCodeRepository;
        $this->_clientRepository = $clientRepository;
        $this->_refreshTokenRepository = $refreshTokenRepository;
        $this->_scopeRepository = $scopeRepository;
        $this->_sessionRepository = $sessionRepository;
        $this->_userClientRepository = $userClientRepository;
        $this->_userRepository = $userRepository;
    }

    public function authorize($form)
    {
        $showView = false;
        // 是否用户手动确认授权
        $confirmAuthorize = env('AUTHORIZE_CONFIRM', true);
        // 使用授权码
        $this->_authorizationServer->enableGrantType(
            new AuthCodeGrant(
                $this->_authCodeRepository,
                $this->_refreshTokenRepository,
                new \DateInterval('PT10M')
            ),
            new \DateInterval('PT1H')
        );
        // 验证请求参数
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_psrRequest);
        $form->client = $client = $authRequest->getClient();
        $form->sessionUser = $sessionUser = $this->_sessionRepository->getUser();

        if($confirmAuthorize) {
            // 需要确认时，页面显示
            return view('oauth2.authorize', ['form' => $form]);
        } else if($sessionUser) {
            // 自动确认时，用户已登录，检查用户是否对应用授权过，如果未授权显示确认页面
            $isAuthorized = $this->_userClientRepository->isAuthorized($sessionUser, $client);
            if(empty($isAuthorized)) {
                // 首次确认，页面显示
                return view('oauth2.authorize', ['form' => $form]);
            }

            // Once the user has logged in set the user on the AuthorizationRequest
            $authRequest->setUser($sessionUser);
            // Once the user has approved or denied the client update the status
            // (true = approved, false = denied)
            $authRequest->setAuthorizationApproved(true);
            // Return the HTTP redirect response
            return $this->_authorizationServer->completeAuthorizationRequest($authRequest, $this->_psrResponse);
        }

        // 显示页面
        return view('oauth2.authorize', ['form' => $form]);
    }

    /**
     * 用户确认登录
     */
    public function login($form)
    {
        // 使用授权码
        $grant = new AuthCodeGrant($this->_authCodeRepository, $this->_refreshTokenRepository, new \DateInterval('PT10M'));
        $this->_authorizationServer->enableGrantType($grant, new \DateInterval('PT1H'));
        // 验证请求参数
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_psrRequest);
        $form->client = $client = $authRequest->getClient();
        // $form->sessionUser = $sessionUser = $this->_sessionRepository->getUser();
        $loginTimes = $this->_sessionRepository->loginTimes();
        $hasUser = $this->_sessionRepository->hasUser();
        if($hasUser) {
            $user = $this->_sessionRepository->getUser();
        } else {
            // 验证用户名密码
            $user = $this->_userRepository->getUserEntityByUserCredentials($form->username, $form->password, $grant->getIdentifier(), $client);
            if(empty($user)) {
                // 验证失败
                $this->_sessionRepository->incLoginTimes();
                $form->error = '登录失败';
                return view('oauth2.authorize', ['form' => $form]);
            }

            $this->_sessionRepository->persistUser($user);
            $this->_sessionRepository->revokeLoginTimes();
        }
            
        // Once the user has logged in set the user on the AuthorizationRequest
        $authRequest->setUser($user);

        // Once the user has approved or denied the client update the status
        // (true = approved, false = denied)
        $authRequest->setAuthorizationApproved(true);

        // Return the HTTP redirect response
        return $this->_authorizationServer->completeAuthorizationRequest($authRequest, $this->_psrResponse);
        // $this->
        if(empty($form->username) || empty($form->password)) {
            $this->_session->put('loginTimes', ++$loginTimes);
            $form->loginTimes = $loginTimes;
            return [false, null];
        }

        $seesionUser = new UserEntity();
        // $seesionUser->getIdentifier();
        $this->_session->put('user', $seesionUser);
        $this->_session->forget('loginTimes');

        return [false, $seesionUser];
    }

    public function logout($form)
    {
        // 使用授权码
        $this->_authorizationServer->enableGrantType(
            new AuthCodeGrant(
                $this->_authCodeRepository,
                $this->_refreshTokenRepository,
                new \DateInterval('PT10M')
            ),
            new \DateInterval('PT1H')
        );
        // 验证请求参数
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_psrRequest);
        $form->client = $client = $authRequest->getClient();
        $hasUser = $this->_sessionRepository->hasUser();
        if($hasUser) {
            $this->_sessionRepository->revokeUser();
        }

        $form->sessionUser = $sessionUser = $this->_sessionRepository->getUser();
        // 显示页面
        return view('oauth2.authorize', ['form' => $form]);
    }
}