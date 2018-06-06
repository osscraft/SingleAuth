<?php

namespace Dcux\Admin\Action\Ajax\Stat;

use Autoloader;
use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatService;
use Dcux\SSO\Service\SessionService;

class Online extends AjaxPermission
{
    protected $statService;
    public function onCreate()
    {
        parent::onCreate();
        $this->statService = StatService::getInstance();
    }
    public function onGet()
    {
        //$this->template->push($stat);
        $this->onPost();
    }
    public function onPost()
    {
        $key = empty($_REQUEST['key']) ? 'list' : $_REQUEST['key'];
        switch ($key) {
            case 'current':
                $this->currentOnline();
                break;
            case 'list':
            default:
                $this->listOnline();
                break;
        }
    }
    protected function currentOnline()
    {
        $data = array();
        $i = 0;
        $num = 1;
        $ret = $this->statService->getStatOnlineList($num);
        if (!empty($ret)) {
            foreach ($ret as $k => $v) {
                $data[$i] = array($num - $i - 1, intval($v['count']));
                $i++;
            }
        }
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
    protected function listOnline()
    {
        $data = array();
        $i = 0;
        $num = empty($_REQUEST['num']) ? 180 : $_REQUEST['num'];
        $ret = $this->statService->getStatOnlineList($num);
        if (!empty($ret)) {
            foreach ($ret as $k => $v) {
                //$data[$i] = array($num - $i - 1, intval($v['count']));
                $data[$i] = array(strtotime($v['time']) * 1000, intval($v['count']));
                $i++;
            }
        }
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
}
// PHP END
