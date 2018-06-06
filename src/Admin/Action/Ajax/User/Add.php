<?php

namespace Dcux\Admin\Action\Ajax\User;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\User;
use Dcux\SSO\Service\UserService;

class Add extends AjaxPermission
{
    protected $userService;
    public function onCreate()
    {
        $this->userService = UserService::getInstance();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $info = array();
        $info['uid'] = $uid = empty($_REQUEST['uid']) ? '' : $_REQUEST['uid'];
        $info['username'] = $username = empty($_REQUEST['username']) ? '' : $_REQUEST['username'];
        $info['isAdmin'] = $isAdmin = empty($_REQUEST['isAdmin']) ? '' : $_REQUEST['isAdmin'];

        if (empty($uid)) {
            $this->template->push('code', 501002);
            $this->template->push('error', 'invalid user uid');
        } elseif (empty($username)) {
            $this->template->push('code', 501003);
            $this->template->push('error', 'invalid user username');
        } else {
            $ret = $this->userService->add($info);
            if (empty($ret)) {
                $this->template->push('code', 500001);
                $this->template->push('error', 'add failure');
            } else {
                $this->template->push('data', 'success');
            }
        }
    }
}
// PHP END
