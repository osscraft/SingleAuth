<?php

namespace Dcux\Admin\Action\Ajax\User;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\User;
use Dcux\SSO\Service\UserService;

class Del extends AjaxPermission
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
        $uid = empty($_REQUEST['uid']) ? 0 : $_REQUEST['uid'];

        if (empty($uid)) {
            $this->template->push('code', 501001);
            $this->template->push('error', 'invalid uid');
        } else {
            $ret = $this->userService->del($uid);
            if (empty($ret)) {
                $this->template->push('code', 50002);
                $this->template->push('error', 'delete failure');
            } else {
                $this->template->push('data', 'success');
            }
        }
    }
}
// PHP END
