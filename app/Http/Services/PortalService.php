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
use League\OAuth2\Client\Provider\GenericProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PortalService
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
    /**
     * @var GenericProvider
     */
    private $_provider;

    public function __construct(SecurityHelper $securityHelper, Request $request, ServerRequestInterface $psrRequest, ResponseInterface $psrResponse, ClientRepository $clientRepository, AccessTokenRepository $accessTokenRepository, ScopeRepository $scopeRepository, AuthCodeRepository $authCodeRepository, RefreshTokenRepository $refreshTokenRepository, SessionRepository $sessionRepository, UserClientRepository $userClientRepository, UserRepository $userRepository)
    {
        $this->_securityHelper = $securityHelper;
        $this->_request = $request;
        $this->_session = $request->session();
        $this->_psrRequest = $psrRequest;
        $this->_psrResponse = $psrResponse;
        $this->_accessTokenRepository = $accessTokenRepository;
        $this->_authCodeRepository = $authCodeRepository;
        $this->_clientRepository = $clientRepository;
        $this->_refreshTokenRepository = $refreshTokenRepository;
        $this->_scopeRepository = $scopeRepository;
        $this->_sessionRepository = $sessionRepository;
        $this->_userClientRepository = $userClientRepository;
        $this->_userRepository = $userRepository;
        $this->_provider = new GenericProvider([
            'clientId'                => 'myawesomeapp',    // The client ID assigned to you by the provider
            'clientSecret'            => md5('abc123'),   // The client password assigned to you by the provider
            'redirectUri'             => url('/index'),
            'urlAuthorize'            => url('/authorize'),
            'urlAccessToken'          => url('/token'),
            'urlResourceOwnerDetails' => url('/resource'),
        ]);
    }

    public function index($form)
    {
        if (!isset($_GET['code'])) {
            $authorizationUrl = $this->_provider->getAuthorizationUrl();
            $state = $this->_provider->getState();
            // 保存state值
            $this->_session->put('oauth2.state', $state);

            if($form->show) {
                return $authorizationUrl;
            }
            return redirect($authorizationUrl);
        }
        if(empty($_GET['state'])) {
            throw new \Exception(OAUTH2_ERR_110);
        }
        $state = $this->_session->get('oauth2.state');
        if($state != $_GET['state']) {
            throw new \Exception(OAUTH2_ERR_110);
        }

        try {
            $form->accessToken = $accessToken = $this->_provider->getAccessToken(OAuth2Service::GRANT_TYPE_AUTH_CODE, [
                'code' => $_GET['code']
            ]);
            $form->resourceOwner = $resourceOwner = $this->_provider->getResourceOwner($accessToken);

        } catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            dd($e);
            throw new \Exception(OAUTH2_ERR_110);
        }

        return view('portal.index', ['form' => $form]);
    }
}
