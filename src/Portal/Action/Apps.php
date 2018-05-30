<?php

namespace Dcux\Portal\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;

use Dcux\Portal\Kernel\PAction;
use Dcux\SSO\Service\ClientService;

class Apps extends PAction {
    public function onGet() {
        $out = array();
        if(empty($_REQUEST['fromsession'])) {
            $role = empty($_REQUEST['role']) ? 0 : $_REQUEST['role'];
            $role = empty($role) ? 0 : ($role == 'teacher' ? 1 : ($role == 'student' ? 2 : ($role == 'other' ? 3 : 0)));
        } else {
            if (! empty($_SESSION['uid'])) {
                $role = empty($_SESSION['role']) ? 0 : $_SESSION['role'];
                $role = empty($role) ? 0 : ($role == '教师' ? 1 : ($role == '学生' ? 2 : ($role == '其他' ? 3 : 0)));
            } else {
                $role = 0;
            }
        }
        
        $out["list"] = ClientService::readClientByRole($role);
        $out["total"] = count($out["list"]);
        $out["since"] = '0';
        $rsp = array();
        $rsp['code'] = 0;
        $rsp['data'] = $out;
        //$rsp['pass'] = md5('1qa2ws3ed');
        //$rsp['msg'] = '';
        $this->template->push($rsp);
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END