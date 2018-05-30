<?php

namespace Dcux\SSO\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Syslog;

use Dcux\SSO\Kernel\SAction;
use Dcux\SSO\Model\StatUserDetail;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Service\UserExtensionService;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatUserService;
use Dcux\SSO\Service\StatUserDetailService;
use Dcux\SSO\Service\StatService;
use Dcux\SSO\Log\LogContainer;

abstract class UAction extends SAction {
	protected $userService;
	protected $userExtensionService;
    protected $statService;
    public function onCreate() {
		$this->userService=UserService::getInstance();
		$this->userExtensionService=UserExtensionService::getInstance();
        $this->statService = StatService::getInstance();
        parent::onCreate();
    }
    protected function logVisit($client, $response_type, $valid = true, $grant_type = null) {
        global $CFG;
        $client_id = empty($client['clientId']) ? '' : $client['clientId'];
        $client_type = empty($client['clientType']) ? '' : $client['clientType'];
        $redirect_uri = empty($client['redirectURI']) ? '' : $client['redirectURI'];
        if ($valid) {
            //if(empty($CFG['cron_open'])) {
            StatClientService::addByDay(array (
                    'date' => date("Y-m-d", time()),
                    'clientId' => $client_id 
            ), 0);
            //}
        }
        if(!empty($CFG['log_by_syslog'])) {
            $arr = array(
                'client_id'=>$client_id,
                'client_type'=>$client_type,
                'response_type'=>$response_type,
                'redirect_uri'=>$redirect_uri,
                'success'=>$valid
            );
            if(!is_null($grant_type)) {
                $arr['grant_type'] = $grant_type;
            }
            Syslog::logApp($arr);
        }
    }
    protected function logLogin($client, $uid, $success = true, $by = 0) {
        global $CFG;
        $client_id = $client['clientId'];
        $client_type = $client['clientType'];
        $redirect_uri = $client['redirectURI'];
        $username = $uid;
        if ($success) {
            //if(empty($CFG['cron_open'])) {
			StatClientService::addByDay(array (
                    'date' => date("Y-m-d", time()),
                    'clientId' => $client_id 
            ), 1);
            StatUserService::addByDay(array (
                    'username' => $username,
                    'date' => date("Y-m-d", time()) 
            ));
            //}
            $this->statService->increaseStatBrowser();
        }
		StatUserDetailService::write(array (
                'clientId' => $client_id,
                'username' => $username,
                'loginBy' => $by,
                'isPassword' => $by == StatUserDetail::LOGIN_BY_PASSWORD ? 1 : 0,
                'success' => $success 
        ));
        if(!empty($CFG['log_by_syslog'])) {
            Syslog::logResourceOwner(array(
                'client_id' => $client_id,
                'username' => $username,
                'success' => $success
            ));
        }
    }
    // 真实验证用户名密码后更新
    protected function updateLastLogin($uid, $client) {
        $client_id = $client['clientId'];
		$this->userExtensionService->updateLastLogin($uid, $client_id);
    }
    protected function errorResponse($error, $error_description = null, $error_uri = null) {
        global $CFG;
        $result['error'] = $error;
        
        if (! empty($CFG['display_error']) && $error_description)
            $result["error_description"] = $error_description;
        
        if (! empty($CFG['display_error']) && $error_uri)
            $result["error_uri"] = $error_uri;
        
        $this->template->push($result);
    }
}