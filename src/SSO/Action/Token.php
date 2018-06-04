<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;

use Dcux\SSO\Kernel\UAction;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\ScopeService;
use Dcux\SSO\Service\OAuth2CodeService;
use Dcux\SSO\Service\OAuth2TokenService;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatUserService;
use Dcux\SSO\Service\StatUserDetailService;
use Dcux\SSO\Service\IdentifyService;

class Token extends UAction {
    protected $clientService;
    protected $scopeService;
    protected $oauth2CodeService;
    protected $oauth2TokenService;
    protected $identify;
    public function onCreate() {
        $this->clientService = ClientService::getInstance();
        $this->scopeService = ScopeService::getInstance();
        $this->oauth2CodeService = OAuth2CodeService::getInstance();
        $this->oauth2TokenService = OAuth2TokenService::getInstance();
        $this->identify = IdentifyService::getInstance();
        parent::onCreate();
    }
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        global $CFG;
        $userid = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_USER_ID]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_USER_ID];
        $userid = $userid ? $userid : false;
        $username = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_USERNAME]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_USERNAME];
        $username = $username ? $username : false;
        $password = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_PASSWORD]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_PASSWORD];
        $password = $password ? $password : '';
        $code = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_CODE]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_CODE];
        $refreshToken = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_REFRESH_TOKEN]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_REFRESH_TOKEN];
        $useRefresh = App::get('oauth2.use_refresh_token', true);
        
        $scope = OAuth2::getRequestScope();
        $requestType = OAuth2::getRequestType();
        // $sUser = OAuth2::getSessionUser($request, $response);
        $responseType = OAuth2::getResponseType();
        $clientId = OAuth2::getClientId();
        $clientType = $responseType == OAuth2::RESPONSE_TYPE_TOKEN ? OAuth2::CLIENT_TYPE_IMPLICIT : OAuth2::CLIENT_TYPE_WEB;
        $redirectURI = OAuth2::getRedirectURI();
        $redirectURI = trim($redirectURI);
        $clientSecret = OAuth2::getClientSecret();
        $check = OAuth2::checkRequest($requestType);
        $clientType = $requestType == OAuth2::REQUEST_TYPE_TOKEN ? OAuth2::CLIENT_TYPE_WEB : ($requestType == OAuth2::REQUEST_TYPE_PASSWORD ? OAuth2::CLIENT_TYPE_DESKTOP : false);
        
        if ($check) {
            $client = $this->clientService->checkClient($clientId, $clientType, $redirectURI, $clientSecret);
            if ($client) {
                $identify = App::get('identify_database', 'identify');
                if ($requestType == OAuth2::REQUEST_TYPE_TOKEN && $code) {
                    $oauth2code = $this->oauth2CodeService->get($code);
                    if ($oauth2code && $oauth2code['clientId'] == $clientId) {
                        list($scopeStr, $scopeArr) = $this->scopeService->filter($oauth2code['scope']);
                        // $user = $this->userService->get($oauth2code['userid']);
                        $user = $this->identify->getUser($oauth2code['username'], $oauth2code['scope']);
                        $params = $this->genTokenParam($user, $client, $scopeStr);
                        $this->template->push($params);
                        // 注册删除已经使用过的code
                        App::$_event->listen(get_class(App::$_app), App::E_FINISH, array($this, 'releaseCode'), array($code));
                    } else {
                        $this->errorResponse('invalid_grant');
                    }
                } else if ($requestType == OAuth2::REQUEST_TYPE_PASSWORD && $password) {
                    // log visit count
                    $this->logVisit($client, $responseType);
                    // $user = $this->userService->checkUser($password, $userid, $username);
                    list($scopeStr, $scopeArr) = $this->scopeService->filter($client['clientScope']);
                    $user = $this->identify->verifyResourceOwner($username, $password, $client['clientScope']);
                    if ($user) {
                        list ( $scopeStr, $scopeArr ) = $this->scopeService->filter($client['clientScope']);
                        $this->updateSessionUser($user);
                        $params = $this->genTokenParam($user, $client, $scopeStr);
                        $this->template->push($params);
                        $this->logLogin($client, $username, true);
                    } else {
                        $this->errorResponse('invalid_grant');
                        $this->logLogin($client, $username, false);
                    }
                } else if ($requestType == OAuth2::REQUEST_TYPE_REFRESH_TOKEN && $refreshToken) {
                    // log visit count
                    $this->logVisit($client, $responseType);
                    $oauth2token = $this->oauth2TokenService->get($refreshToken);
                    if ($oauth2token && $oauth2token['type'] == OAuth2::TOKEN_TYPE_REFRESH) {
                        list ( $scopeStr, $scopeArr ) = $this->scopeService->filter($oauth2token['scope']);
                        // $user = $this->userService->get($oauth2token['username']);
                        $user = $this->identify->verifyResourceOwner($username, $password, $client['clientScope']);
                        $params = $this->genAccessTokenParam($user, $client, $scopeStr);
                        $this->template->push($params);
                    } else {
                        $this->errorResponse('invalid_grant');
                    }
                } else {
                    $this->errorResponse('invalid_grant');
                }
            } else {
                $this->errorResponse('invalid_client');
            }
        } else {
            $this->errorResponse('invalid_request');
        }
    }
    protected function genTokenParam($user, $client, $scope) {
        // $scope = is_string($scope) ? : (is_array($scope)?implode(',', array_keys($scope)):'');
        $params = $this->genAccessTokenParam($user, $client, $scope);
        if (App::get('use_refresh_token', true)) {
            $p = $this->genRefreshTokenParam($user, $client, $scope);
            $params = array_merge($params, $p);
        }
        return $params;
    }
    protected function genAccessTokenParam($user, $client, $scope) {
        $redirectURI = $client['redirectURI'];
        // $scope = is_string($scope) ? : (is_array($scope)?implode(',', array_keys($scope)):'');
        $accessToken = $this->oauth2TokenService->gen($user, $client, $scope);
        return $accessToken;
    }
    protected function genRefreshTokenParam($user, $client, $scope) {
        // $scope = is_string($scope) ? : (is_array($scope)?implode(',', array_keys($scope)):'');
        $refreshToken = $this->oauth2TokenService->genRefresh($user, $client, $scope);
        $params = array ();
        if ($refreshToken) {
            $params[OAuth2::HTTP_QUERY_PARAM_REFRESH_TOKEN] = $refreshToken['token'];
            // $params['refresh_expires'] = $refreshToken['expires'];
        }
        return $params;
    }
    protected function genCodeParam($user, $client, $scope) {
        // $scope = is_string($scope) ? : (is_array($scope)?implode(',', array_keys($scope)):'');
        $oauth2code = $this->oauth2CodeService->gen($user, $client, $scope);
        $params = array ();
        if (is_array($oauth2code)) {
            $params[OAuth2::HTTP_QUERY_PARAM_CODE] = $oauth2code['code'];
        } else if (is_string($oauth2code)) {
            $params[OAuth2::HTTP_QUERY_PARAM_CODE] = $oauth2code;
        }
        return $params;
    }
    // 清除使用过的code
    public function releaseCode($code, $app) {
        if(!empty($code)) {
            $this->oauth2CodeService->del($code);
        }
    }
}
// PHP END