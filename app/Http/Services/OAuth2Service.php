<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Http\Services;

use App\Entities\ThirdEntity;
use App\Entities\UserEntity;
use App\Helper\SecurityHelper;
use App\Repositories\AccessTokenRepository;
use App\Repositories\AuthCodeRepository;
use App\Repositories\ClientRepository;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\ScopeRepository;
use App\Repositories\SessionRepository;
use App\Repositories\UserClientRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OAuth2Service
{
    /**
     * @var SecurityHelper
     */
    private $_securityHelper;
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

    public function __construct(SecurityHelper $securityHelper, Request $request, ServerRequestInterface $psrRequest, ResponseInterface $psrResponse, ClientRepository $clientRepository, AccessTokenRepository $accessTokenRepository, ScopeRepository $scopeRepository, AuthCodeRepository $authCodeRepository, RefreshTokenRepository $refreshTokenRepository, SessionRepository $sessionRepository, UserClientRepository $userClientRepository, UserRepository $userRepository)
    {
        $privateKey = env('APP_PRIVATE_KEY');
        $encryptionKey = env('APP_KEY');

        $this->_securityHelper = $securityHelper;
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
        $form->sessionUser = $user = $this->_sessionRepository->getUser();
        if($confirmAuthorize) {
            // 需要确认时，页面显示
            return view('oauth2.authorize', ['form' => $form]);
        } else if(!empty($user)) {
            // 自动确认时，用户已登录，检查用户是否对应用授权过，如果未授权显示确认页面
            $isAuthorized = $this->_userClientRepository->isAuthorized($user, $client);
            if(empty($isAuthorized)) {
                // 首次确认，页面显示
                return view('oauth2.authorize', ['form' => $form]);
            }

            // Once the user has logged in set the user on the AuthorizationRequest
            $authRequest->setUser($user);
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
        $maxLoginCount = env('LOGIN_COUNT', 3);
        // 使用授权码
        $grant = new AuthCodeGrant($this->_authCodeRepository, $this->_refreshTokenRepository, new \DateInterval('PT10M'));
        $this->_authorizationServer->enableGrantType($grant, new \DateInterval('PT1H'));
        // 验证请求参数
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_psrRequest);

        $form->client = $client = $authRequest->getClient();
        $form->sessionUser = $user = $this->_sessionRepository->getUser();
        $form->loginCount = $this->_sessionRepository->getLoginCount();
        $form->lastAttemptTime = $this->_sessionRepository->getLastAttemptTime();
        if($form->loginCount > $maxLoginCount && !empty($form->lastAttemptTime) && time() - $form->lastAttemptTime < 1800) {
            // 设置信息
            $form->error = '请半小时后重试';
            return view('oauth2.authorize', ['form' => $form]);
        }
        if(empty($user)) {
            // 验证用户名密码
            $user = $this->_userRepository->getUserEntityByUserCredentials($form->username, $form->password, $grant->getIdentifier(), $client);
            if(empty($user)) {
                // 验证失败
                $this->_sessionRepository->incLoginCount();
                $this->_sessionRepository->persistLastAttemptTime();
                // 设置信息
                $form->error = '用户名或密码错误';
                return view('oauth2.authorize', ['form' => $form]);
            }

            $this->_sessionRepository->persistUser($user);
            $this->_sessionRepository->revokeLoginCount();
            $this->_sessionRepository->revokeLastAttemptTime();
        }
            
        // Once the user has logged in set the user on the AuthorizationRequest
        $authRequest->setUser($user);
        // Once the user has approved or denied the client update the status
        // (true = approved, false = denied)
        $authRequest->setAuthorizationApproved(true);
        // Return the HTTP redirect response
        return $this->_authorizationServer->completeAuthorizationRequest($authRequest, $this->_psrResponse);
    }

    /**
     * 登出
     */
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

        $this->_sessionRepository->revokeUser();

        $form->client = $client = $authRequest->getClient();
        $form->sessionUser = $sessionUser = $this->_sessionRepository->getUser();
        // 显示页面
        return view('oauth2.authorize', ['form' => $form]);
    }

    public function qrlogin($form)
    {
        $maxLoginCount = env('LOGIN_COUNT', 3);
        // 使用授权码
        $grant = new AuthCodeGrant($this->_authCodeRepository, $this->_refreshTokenRepository, new \DateInterval('PT10M'));
        $this->_authorizationServer->enableGrantType($grant, new \DateInterval('PT1H'));
        // 验证请求参数
        $authRequest = $this->_authorizationServer->validateAuthorizationRequest($this->_psrRequest);

        $form->client = $client = $authRequest->getClient();
        $form->sessionUser = $user = $this->_sessionRepository->getUser();
        $form->loginCount = $this->_sessionRepository->getLoginCount();
        $form->lastAttemptTime = $this->_sessionRepository->getLastAttemptTime();
        if($form->loginCount > $maxLoginCount && !empty($form->lastAttemptTime) && time() - $form->lastAttemptTime < 1800) {
            // 设置信息
            $form->error = '请半小时后重试';
            return view('oauth2.authorize', ['form' => $form]);
        }
        if(empty($user)) {
            // 验证二维码登录签名
            $form->clientId = $client->getIdentifier();
            $form->clientSecret = $this->_clientRepository->getClientSecret($form->clientId);
            $token = $this->_sessionRepository->getToken();
            $resolve = $this->_securityHelper->validQrcodeLoginToken($token);
            if(empty($resolve)) {
                throw new \Exception(QRCODE_ERR_101);
            }
            list($clientId, $form->socketClientId, $form->timestamp) = $resolve;
            if($clientId != $form->clientId) {
                throw new \Exception(QRCODE_ERR_101);
            }
            // $form->sign = $this->_securityHelper->qrcodeLoginSignature($form);
            // dd($form);
            
            $valid = $this->_securityHelper->validQrcodeLoginSignature($form);//$form->valid = $valid;dd($form);
            if(empty($valid)) {
                // 验证失败
                $this->_sessionRepository->incLoginCount();
                $this->_sessionRepository->persistLastAttemptTime();
                // 设置信息
                $form->error = '登录失败';
                return view('oauth2.authorize', ['form' => $form]);
            }
            $user = $this->_userRepository->getUserEntityByUsername($form->username);
            if(empty($user)) {
                // 验证失败
                $this->_sessionRepository->incLoginCount();
                $this->_sessionRepository->persistLastAttemptTime();
                // 设置信息
                $form->error = '登录用户不存在';
                return view('oauth2.authorize', ['form' => $form]);
            }

            $this->_sessionRepository->persistUser($user);
            $this->_sessionRepository->revokeLoginCount();
            $this->_sessionRepository->revokeLastAttemptTime();
        }

            
        // Once the user has logged in set the user on the AuthorizationRequest
        $authRequest->setUser($user);
        // Once the user has approved or denied the client update the status
        // (true = approved, false = denied)
        $authRequest->setAuthorizationApproved(true);
        // Return the HTTP redirect response
        return $this->_authorizationServer->completeAuthorizationRequest($authRequest, $this->_psrResponse);
    }

    /**
     * 通过授权码获取令牌
     */
    public function authcode($form)
    {
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

    /**
     * 与接入应用解绑
     */
    public function unbind($form)
    {
        
    }

    /**
     * 与接入应用绑定
     */
    public function bind($form)
    {
        
    }
}