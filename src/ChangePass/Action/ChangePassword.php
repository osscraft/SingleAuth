<?php
namespace Dcux\ChangePass\Action;

use Dcux\ChangePass\Kernel\App;
use Dcux\ChangePass\Kernel\AAction;
use Dcux\SSO\Service\MysqlUserService;

class ChangePassword extends AAction
{
    protected $mysqlUserService;
    public function onCreate()
    {
        parent::onCreate();
        $this->mysqlUserService = MysqlUserService::getInstance();
    }
    public function onGet()
    {
        global $CFG;
        $out = array();
        $param = $_REQUEST;
        if ($param['key']=='check') {
            $password = $param['password'];
            $uid = $param['uid'];
            $user = $this->mysqlUserService->get($uid);
            $out['SUCCESS']=false;
            if (md5($password)==$user['password']) {
                $out['SUCCESS']=true;
            }
            $this->template->push($out);
        } elseif ($param['key']=='upd') {
            $opassword=$param['opassword'];
            $npassword=$param['npassword'];
            $uid=$param['uid'];
            $out['SUCCESS']=$this->mysqlUserService->upd($uid, array('password'=>md5($npassword)));
            $this->template->push($out);
        }
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
