<?php

namespace Dcux\Api\Kernel;

use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Api\Kernel\Api;
use Dcux\Api\Kernel\SApi;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\OAuth2TokenService;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Service\IdentifyService;
use Dcux\SSO\Service\ScopeService;
use Dcux\Api\Data\VResponse;

// only token parameter

abstract class TokenApi extends Api {
    protected $uid;
    protected $user;
    protected $scopeService;
    protected $token;
    protected $oauth2TokenService;
    public function onCreate() {
        parent::onCreate();
        $this->userService = UserService::getInstance();
        $this->identifyService = IdentifyService::getInstance();
        $this->scopeService = ScopeService::getInstance();
        $this->oauth2TokenService = OAuth2TokenService::getInstance();
    }
    /**
     * 验证不通过，不执行onGet,onPost等方法，需要跳过时请重写此方法
     */
    public function onValidate() {
        $t = empty($_REQUEST['tid']) ? (empty($_REQUEST['token']) ? '' : $_REQUEST['token']) : $_REQUEST['tid'];
        $token = $this->oauth2TokenService->validToken($t);
        if(empty($token)) {
            $this->failure(Errode::invalid_token());
            return false;
        } else {
            $this->token = $token;
            return parent::onValidate();
        }
    }

    // get uid
    protected function getUid() {
        if(empty($this->user) && empty($this->uid)) {
            $user = $this->getUser();
        } else if(!empty($this->user)) {
            $user = $this->user;
        }
        return empty($user) ? false : $user['uid'];
    }
    //get user
    protected function getUser() {
        if(empty($this->user) && empty($this->uid) && empty($this->token)) {
            return false;
        } else if(!empty($this->user)) {
            $user = $this->user;
        } else if(!empty($this->token)) {
            $uid = $this->token['username'];
            $scope = $this->scopeService->filter();
            $user = $this->identifyService->getUser($uid, $scope);
        } else if(!empty($this->uid)) {
            $user = false;
        }
        return empty($user) ? false : $user;
    }
    protected function getToken() {
        return empty($this->token) ? '' : $this->token;
    }
    protected function getClientId() {
        $token = $this->getToken();
        if(empty($token)) {
            return false;
        } else {
            return $this->token['clientId'];
        }
    }
}