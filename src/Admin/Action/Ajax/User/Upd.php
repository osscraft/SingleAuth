<?php

namespace Dcux\Admin\Action\Ajax\User;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\User;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Service\UserService;

class Upd extends AjaxPermission {
	protected $userService;
	public function onCreate() {
		$this->userService = UserService::getInstance();
	}
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        $uid = empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];
        $info = array();
        $info['username'] = $username = empty($_REQUEST['username']) ? '' : $_REQUEST['username'];
        $info['isAdmin'] = $isAdmin = empty($_REQUEST['isAdmin']) ? '' : $_REQUEST['isAdmin'];
        
        if(empty($uid)) {
            $this->template->push('code', 501001);
            $this->template->push('error', 'invalid uid');
        } else if(empty($username)) {
            $this->template->push('code', 501003);
            $this->template->push('error', 'invalid user username');
        } else {
            $ret = $this->userService->upd($uid, $info);
            if(empty($ret)) {
                $this->template->push('code', 500003);
                $this->template->push('error', 'update failure');
            } else {
                $this->template->push('data', 'success');
            }
        }
    }

}
// PHP END