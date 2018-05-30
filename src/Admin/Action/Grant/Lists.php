<?php

namespace Dcux\Admin\Action\Grant;

use Dcux\Admin\Kernel\MenuPermission;
use Dcux\Admin\Action\Grant;
use Dcux\SSO\Service\UserGrantService;
use Dcux\SSO\Service\UserService;

class Lists extends MenuPermission {
    protected $userGrantService;
    public function cmd() {
        return 'grant';
    }
    public function onCreate() {
        parent::onCreate();
        $t = $this->template->getTheme();
        if(empty($t) || $t == 'default') {
            //直接跳至
            $this->template->redirect('/admin/user.php', array(), false);
        }
		$this->userService = UserService::getInstance();
    }
    public function onGet() {
		$users = $this->userService->getQueryList(array('isAdmin'=>1), array('uid' => 'ASC'));
		$this->template->push('users',$users);
		$this->template->file('grant/list.php');
    }
    public function onPost() {
    	$this->onGet();
    }
}
// PHP END