<?php

namespace Dcux\Api\Kernel;

use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Api\Kernel\Api;
use Dcux\Api\Kernel\TApi;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Service\IdentifyService;
use Dcux\SSO\Service\ScopeService;
use Dcux\Api\Data\VResponse;

abstract class SApi extends Api {
    protected $uid;
    protected $user;
    protected $scopeService;
    public function onCreate() {
        parent::onCreate();
        $this->userService = UserService::getInstance();
        $this->identifyService = IdentifyService::getInstance();
        $this->scopeService = ScopeService::getInstance();
    }
    /**
     * 验证不通过，不执行onGet,onPost等方法，需要跳过时请重写此方法
     */
    public function onValidate() {
        $sid = empty($_REQUEST['sid']) ? '' : $_REQUEST['sid'];
        $uid = Security::getUidFromSid($sid);
        if($this instanceof TApi) {
            if(empty($uid) && empty($this->token)) {
                $this->failure(Errode::invalid_token());
                return false;
            } else if(!empty($uid)) {
                $this->uid = $uid;
            }
            return parent::onValidate();
        } else {
            if(empty($uid)) {
                $this->failure(Errode::invalid_sid());
                return false;
            } else {
                $this->uid = $uid;
            }
            return parent::onValidate();
        }
    }
    protected function getUid() {
        return empty($this->uid) ? false : $this->uid;
    }
    protected function getUser() {
        if(empty($this->uid)) {
            return false;
        }
        if(empty($this->user)) {
            $scope = $this->scopeService->filter();
            $user = $this->user = $this->identifyService->getUser($this->uid, $scope);
        } else {
            $user = $this->user;
        }
        return empty($user) ? false : $user;
    }
}