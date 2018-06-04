<?php

namespace Dcux\SSO\Kernel;

use Lay\Advance\Util\Logger;

use Dcux\SSO\Kernel\Action;
use Dcux\SSO\Kernel\MemSession;
use Dcux\SSO\Service\SessionService;

abstract class SAction extends Action {
    protected $hasSession = false;
    protected $sessionService;
    public function onCreate() {
        $this->sessionService = SessionService::getInstance();
        session_set_save_handler(array (
                $this,
                'sopen' 
        ), array (
                $this,
                'sclose' 
        ), array (
                $this,
                'sread' 
        ), array (
                $this,
                'swrite' 
        ), array (
                $this,
                'sdestroy' 
        ), array (
                $this,
                'sgc' 
        ));
        // 下面这行代码可以防止使用对象作为会话保存管理器时可能引发的非预期行为
        register_shutdown_function('session_write_close');
        
        session_start();
        header('Access-Control-Allow-Origin: *');
        parent::onCreate();
    }
    protected function removeSessionUser() {
        unset($_SESSION['uid']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        // session_regenerate_id(true);
    }
    /**
     *
     * @param array $user            
     */
    protected function updateSessionUser($user) {
        $_SESSION['uid'] = $user['uid'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
    }
    protected function updateCookieUser($user) {
        setcookie("ufsso_longmeng_portal_uid", $user['uid']);
        setcookie("ufsso_longmeng_portal_username", $user['username']);
        setcookie("ufsso_longmeng_portal_role", $user['role']);
    }
    protected function checkVerifyCode($verifyCode) {
        if ($_SESSION['verifyCode']) {
            if (strtolower($_SESSION['verifyCode']) == strtolower($verifyCode)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    protected function removeVerifyCode() {
        unset($_SESSION['verifyCode']);
        unset($_SESSION['loginCount']);
    }
    protected function updateLoginCount() {
        $_SESSION['loginCount'] = empty($_SESSION['loginCount']) ? 0 : $_SESSION['loginCount'];
        $loginCount = ++ $_SESSION['loginCount'];
        return $loginCount;
    }
    protected function updateVerifyCode($verifyCode) {
        $_SESSION['verifyCode'] = $verifyCode;
    }
    protected function isNeedVerification() {
        $loginCount = empty($_SESSION['loginCount']) ? 0 : $_SESSION['loginCount'];
        return $loginCount && $loginCount > 1 ? true : false;
    }

    public function sopen($savePath, $sessionName) {
        global $CFG;
        if(empty($CFG['cron_open'])) {
            $this->sessionService->gc();
        }
        $ret = $this->sessionService->open($savePath, $sessionName);
        return empty($ret) ? false : true;
    }
    public function sclose() {
        $ret = $this->sessionService->close();
        return empty($ret) ? false : true;
    }
    public function sread($sessionId) {
        global $CFG;
        if(!empty($CFG['memcache_compatible_date']) && strtotime(date('Y-m-d')) < strtotime($CFG['memcache_compatible_date'])) {
            $se = MemSession::getInstance()->read($sessionId);
        }
        if(empty($se)) {
            $se = $this->sessionService->read($sessionId);
        } else {
            $this->sessionService->destroy($sessionId);
        }
        $this->hasSession = empty($se) ? false : true;
        return $se ? $se : '';
    }
    public function swrite($sessionId, $data) {
        if (empty($_SESSION) && $this->hasSession) {
            // 读取时有，写入时没有则执行删除
            $ret = $this->sessionService->destroy($sessionId);
            return empty($ret) ? false : true;
        } else if(!empty($_SESSION)) {
            //$online = ! empty($_SESSION['uid']) ? 1 : 0;
            $ret = $this->sessionService->write($sessionId, $data);
            return empty($ret) ? false : true;
        }
        return true;
    }
    public function sdestroy($sessionId) {
        $ret = $this->sessionService->destroy($sessionId);
        return empty($ret) ? false : true;
    }
    public function sgc() {
        $ret = $this->sessionService->gc();
        return empty($ret) ? false : true;
    }
}