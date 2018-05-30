<?php

namespace Dcux\Admin\Action\Ajax\Grant;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\UserGrant;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Service\UserGrantService;

class Upd extends AjaxPermission {
	protected $userGrantService;
	public function onCreate() {
        parent::onCreate();
		$this->userGrantService = UserGrantService::getInstance();
	}
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        $uid = empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
		$grants = empty($_REQUEST['uid']) ? 0 : $_REQUEST['grants'];
		if(empty($uid)) {
            $this->template->push('code', 501001);
            $this->template->push('error', 'invalid uid');
		}else if(!empty($grants)){
			$grantStr = implode(";", $grants);
			$isSuper = 0;
			$ret = $this->userGrantService->replace(array('uid'=>$uid,'isSuper'=>0,'grants'=>$grantStr)); 
		}else{
			$ret = $this->userGrantService->del($uid);
		}
		if(empty($ret)) {
			$this->template->push('code', 500003);
			$this->template->push('error', 'update failure');
		} else {
			$this->template->push('data', 'success');
		}
    }

}
// PHP END