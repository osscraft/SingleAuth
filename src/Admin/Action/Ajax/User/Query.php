<?php

namespace Dcux\Admin\Action\Ajax\User;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\User;
use Dcux\SSO\Service\UserService;

class Query extends AjaxPermission
{
    protected $userService;
    public function onCreate()
    {
        $this->userService = UserService::getInstance();
    }
    public function onGet()
    {
        //print_r("expression");exit;
        //parent::onGet();
        //$this->template->file('user/list.php');
        $users = $this->userService->getUserListAll();
        $data = array();
        $pros = array('uid', 'username', 'isAdmin');
        foreach ($users as $user) {
            //$data[] = $this->toPureUserArray($user, $pros);
            $data[]=$user;
        }
        $this->template->push('data', $data);
    }
    public function onPost()
    {
        $this->onGet();
    }

    protected function toPureUserArray($user, $pros = array(), $merge = false)
    {
        if (empty($pros)) {
            return array_values($user);
        } else {
            $arr = array();
            $clone = $user;
            foreach ($pros as $pro) {
                if (isset($user[$pro])) {
                    $arr[] = $clone[$pro];
                    unset($clone[$pro]);
                }
            }
            return empty($clone) || empty($merge) ? $arr : array_merge($arr, array_values($clone));
        }
    }
}
// PHP END
