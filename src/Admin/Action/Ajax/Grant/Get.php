<?php

namespace Dcux\Admin\Action\Ajax\Grant;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\UserGrant;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Service\UserGrantService;

class Get extends AjaxPermission {
	protected $userGrantService;
	public function onCreate() {
        parent::onCreate();
		$this->userGrantService = UserGrantService::getInstance();
	}
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
		$uid = empty($_REQUEST['uid'])?'':$_REQUEST['uid'];
		if(!empty($uid)){
			$userGrant = $this->userGrantService->get($uid);
			$this->template->push('data',$userGrant);
		} else {
			$this->template->push('code', 501001);
            $this->template->push('error', 'invalid uid');
		}
    }

}
// PHP END