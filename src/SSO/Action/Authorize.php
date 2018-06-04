<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;

use Dcux\SSO\Kernel\UAction;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Model\StatUserDetail;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\ScopeService;
use Dcux\SSO\Service\OAuth2CodeService;
use Dcux\SSO\Service\OAuth2TokenService;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatUserService;
use Dcux\SSO\Service\StatUserDetailService;
use Dcux\SSO\Service\IdentifyService;

class Authorize extends UAction {
    protected $clientService;
    protected $scopeService;
    protected $oauth2CodeService;
    protected $oauth2TokenService;
    protected $userService;
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
        global $CFG;
		
        $state = empty($_REQUEST['state']) ? '' : $_REQUEST['state'];
        $sid = empty($_REQUEST['sid']) ? '' : $_REQUEST['sid'];
        $qr = empty($_REQUEST['qr']) ? false : true;
        $scope = OAuth2::getRequestScope();
        $sUser = OAuth2::getSessionUser();
        $responseType = OAuth2::getResponseType();
        $clientId = OAuth2::getClientId();
        $clientType = $responseType == OAuth2::RESPONSE_TYPE_TOKEN ? OAuth2::CLIENT_TYPE_IMPLICIT : OAuth2::CLIENT_TYPE_WEB;
        $redirectURI = OAuth2::getRedirectURI();
        $redirectURI = trim($redirectURI);
        $referer = OAuth2::getReferer();
        $check = OAuth2::checkRequest();
        $isMobile = Utility::isMobile();
        
        if ($check) {
            $client = $this->clientService->checkClient($clientId, $clientType, $redirectURI);
            if ($client) {
                if ($sUser) {
                    $this->template->push('user', $sUser);
					
                } else {
                    $isVerify = $this->isNeedVerification();
                    $this->template->push('is_verify', $isVerify);
                }
                list ( $scopeStr, $scopeArr ) = $this->scopeService->filter($scope);
                // $scopeArr = array_map('intval', explode(',', $client['scope']));
                // $scopes = $this->scopeService->getList($scopeArr);
                // push一些数据
                $this->template->push('CFG', $CFG);
                $this->template->push('LANG', $CFG['LANG']);
                $this->template->push('scope', $scopeArr);
                $this->template->push('client', $client);
                $this->template->push('login_type', 'authorize');
                $this->template->push('response_type', $responseType);
                $this->template->push('client_id', $clientId);
                $this->template->push('state', $state);
                $this->template->push('redirect_uri', $redirectURI);
                $this->template->push('title', $CFG['LANG']['LOGIN'] . $CFG['LANG']['TITLE_SPLIT_SIGN'] . $client['clientName']);
                $this->template->push('referer', $referer);
                $this->template->push('isMobile', $isMobile);
                $this->template->file('authorize.php');
                // 通过sid登录
                if(!empty($sid) && $uid = Security::getUidFromSid($sid)) {
                    $user = $this->identify->getUser($uid);
                    // 更新SESSION
                    $this->updateSessionUser($user);
                    $this->template->redirect($this->genRedirect($redirectURI, $responseType, $user, $client, $scopeStr, $state));
                    if(empty($qr)) {
                        $this->logLogin($client, $user['uid'], true, StatUserDetail::LOGIN_BY_SID);
                    } else {
                        $this->logLogin($client, $user['uid'], true, StatUserDetail::LOGIN_BY_QR);
                    }
                } else if($sUser && ($CFG['skip_if_has_logined'] || ($CFG['skip_if_has_authorized'] && $this->oauth2TokenService->checkTokenByClientAndUser($clientId, $sUser['uid'])))){
					$this->template->push("delay",$CFG['skip_delay']);
					$this->template->file("authorize.php");
                    // 是否跳过确认
                    // 延迟跳转
                    /*if(!empty($CFG['skip_delay'])) {
                        $this->template->delay($CFG['skip_delay'], 'authorize.php');
                    }*/
					
					//提交表单，不生成code
                    //$this->template->redirect($this->genRedirect($redirectURI, $responseType, $sUser, $client, $scopeStr, $state));// login count
                    //$this->logLogin($client, $sUser['uid'], true, StatUserDetail::LOGIN_BY_DELAY);
                }
                // visit count
                $this->logVisit($client, $responseType);
            } else {
                $_client = array('clientId' => $clientId, 'clientType' => $clientType, 'redirectURI' => $redirectURI);
                $this->logVisit($_client, $responseType, false);
                // not log vist if invalid client
                $this->errorResponse('invalid_client');
            }
        } else {
            $this->errorResponse('invalid_request');
        }
    }
    public function onPost() {
        global $CFG;
        /**
         * 检测使用其他账号登录
         */
        $other = empty($_REQUEST['otherlogin']) ? false : $_REQUEST['otherlogin'];
        $register = empty($_REQUEST['register']) ? false : $_REQUEST['register'];
        $state = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_STATE]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_STATE];
        $loginCount = empty($_SESSION['loginCount']) ? 0 : $_SESSION['loginCount'];
        $requestType = OAuth2::REQUEST_TYPE_POST;
        $userid = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_USER_ID]) ? false : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_USER_ID];
        $userid = $userid ?  : false; // false表示不使用此字段作为验证条件
        $username = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_USERNAME]) ? false : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_USERNAME];
        $username = $username ?  : false; // false表示不使用此字段作为验证条件
        $password = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_PASSWORD]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_PASSWORD];
        $password = $password ?  : '';
        $verifyCode = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_VERIFY_CODE]) ? '' : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_VERIFY_CODE];
        $verifyCode = $verifyCode ?  : '';
        
        $scope = OAuth2::getRequestScope();
        $sUser = OAuth2::getSessionUser();
        $responseType = OAuth2::getResponseType();
        $clientId = OAuth2::getClientId();
        $clientType = $responseType == OAuth2::RESPONSE_TYPE_TOKEN ? OAuth2::CLIENT_TYPE_IMPLICIT : OAuth2::CLIENT_TYPE_WEB;
        $redirectURI = OAuth2::getRedirectURI();
        $redirectURI = trim($redirectURI);
        $referer = OAuth2::getReferer(true);
        $check = OAuth2::checkRequest($requestType);
        $isMobile = Utility::isMobile();
        
        if ($other) {
            // 清除验证码,同时也清空登录失败次数
            $this->removeVerifyCode();
            // 清除seesion memcache user,清除cookie
            $this->removeSessionUser();
            $params = array (
                    OAuth2::HTTP_QUERY_PARAM_CLIENT_ID => $clientId,
                    OAuth2::HTTP_QUERY_PARAM_RESPONSE_TYPE => $responseType,
                    OAuth2::HTTP_QUERY_PARAM_REDIRECT_URI => $redirectURI,
                    OAuth2::HTTP_QUERY_PARAM_SCOPE => $scope,
                    OAuth2::HTTP_QUERY_PARAM_STATE => $state 
            );
            // 跳转至认证页
            $this->template->redirect($this->name, $params);
        } else if ($register) {
            $this->template->redirect(App::get('urls.register', $this->name));
        } else if ($check) {
            // 检测客户端信息
            $client = $this->clientService->checkClient($clientId, $clientType, $redirectURI);
            if ($client) {
                // log visit count
                $this->logVisit($client, $responseType);
                list ( $scopeStr, $scopeArr ) = $this->scopeService->filter($scope);
                if ($sUser) {
                    // 已经登录，跳转
                    $this->template->redirect($this->genRedirect($redirectURI, $responseType, $sUser, $client, $scopeStr, $state));
                    // login count
                    $this->logLogin($client, $sUser['uid'], true, StatUserDetail::LOGIN_BY_SESSION);
                    //$this->updateLastLogin($sUser['uid'], $client);
                } else {
                    // 打开检测 登录用户任务
                    $user = $this->identify->verifyResourceOwner($username, $password, $client['clientScope']);
                    if ($user && $this->checkVerifyCode($verifyCode)) {
                        // 清除验证码,同时也清空登录失败次数
                        $this->removeVerifyCode();
                        // 更新SESSION
                        $this->updateSessionUser($user);
                        $this->template->redirect($this->genRedirect($redirectURI, $responseType, $user, $client, $scopeStr, $state));
                        // login count
                        $this->logLogin($client, $user['uid'], true, StatUserDetail::LOGIN_BY_PASSWORD);
                        $this->updateLastLogin($user['uid'], $client);
                    } else {
                        // $this->template->push('user', $user);
                        // 更新登录失败次数
                        $count = $this->updateLoginCount();
                        $isVerify = $this->isNeedVerification();
                        // 错误信息
                        $error = $user ? '验证码错误' : '用户名密码错误';
                        
                        $this->template->push('login_count', $count);
                        $this->template->push('is_verify', $isVerify);
                        // 重新登录，可做
                        $this->template->push('CFG', $CFG);
                        $this->template->push('LANG', $CFG['LANG']);
                        $this->template->push('error', $error);
                        $this->template->push('scope', $scopeArr);
                        $this->template->push('login_type', 'authorize');
                        $this->template->push('client', $client);
                        $this->template->push('response_type', $responseType);
                        $this->template->push('client_id', $clientId);
                        $this->template->push('redirect_uri', $redirectURI);
                        $this->template->push('title', $CFG['LANG']['LOGIN'] . $CFG['LANG']['TITLE_SPLIT_SIGN'] . $client['clientName']);
                        $this->template->push('referer', $referer);
                        $this->template->push('isMobile', $isMobile);
                        $this->template->file('authorize.php');
                        // login count
                        $this->logLogin($client, $username, false, StatUserDetail::LOGIN_BY_PASSWORD);
                    }
                }
            } else {
                $_client = array('clientId' => $clientId, 'clientType' => $clientType, 'redirectURI' => $redirectURI);
                // log failure
                $this->logVisit($_client, $responseType, false);
                // not log vist if invalid client
                $this->errorResponse('invalid_client');
            }
        } else {
            $this->errorResponse('invalid_request');
        }
    }
    protected function genRedirect($redirectURI, $responseType, $user, $client, $scopeStr, $state = '') {
        if ($responseType == OAuth2::RESPONSE_TYPE_TOKEN) {
            // 生成token
            $params = $this->genTokenParam($user, $client, $scopeStr);
            $params = empty($state) ? $params : array_merge($params, array (
                    'state' => $state 
            ));
            $redirect = $redirectURI . '#' . http_build_query($params);
        } else {
            // 生成code
            $params = $this->genCodeParam($user, $client, $scopeStr);
            $params = empty($state) ? $params : array_merge($params, array (
                    'state' => $state 
            ));
            $redirect = Utility::url($redirectURI, $params);
        }
        return $redirect;
    }
    protected function genParam($responseType, $user, $client, $scopeStr, $state = '') {
        if ($responseType == OAuth2::RESPONSE_TYPE_TOKEN) {
            // 生成token
            $params = $this->genTokenParam($user, $client, $scopeStr);
            $params = empty($state) ? $params : array_merge($params, array (
                    'state' => $state 
            ));
        } else {
            // 生成code
            $params = $this->genCodeParam($user, $client, $scopeStr);
            $params = empty($state) ? $params : array_merge($params, array (
                    'state' => $state 
            ));
        }
        return empty($params) ? array() : $params;
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
}
// PHP END