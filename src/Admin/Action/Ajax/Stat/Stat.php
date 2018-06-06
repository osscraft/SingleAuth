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

class Stat extends AjaxPermission
{
    protected $clientService;
    protected $userManager;
    protected $statClientService;
    protected $statService;
    protected $sessionService;
    public function onCreate()
    {
        parent::onCreate();
        $this->clientService = ClientService::getInstance();
        $this->userService = UserService::getInstance();
        $this->statClientService = StatClientService::getInstance();
        $this->statService = StatService::getInstance();
        $this->sessionService = SessionService::getInstance();
    }
    public function onGet()
    {
        //$this->template->push($stat);
        $this->onPost();
    }
    public function onPost()
    {
        $key = empty($_REQUEST['key']) ? 'online' : $_REQUEST['key'];
        if ($key == 'client_top') {
            $this->loadClientTop();
        } elseif ($key == 'browser_top') {
            $this->loadBrowser();
        } elseif ($key == 'browser_d3') {
            $this->loadBrowserD3();
        } elseif ($key == 'online') {
            $this->loadOnline();
        }
    }

    protected function loadBrowserD3()
    {
        $ret = $this->statService->getStatBrowserDistribution();
        $conduct = array();
        foreach ($ret as $v) {
            $conduct[$v['browser']] = empty($conduct[$v['browser']]) ? array() : $conduct[$v['browser']];
            $conduct[$v['browser']]['name'] = $v['browser'];
            $conduct[$v['browser']]['children'] = empty($conduct[$v['browser']]['children']) ? array() : $conduct[$v['browser']]['children'];
            $conduct[$v['browser']]['children'][] = array('name' => $v['version'], 'size' => intval($v['count']));
        }
        $data = array();
        $data['name'] = 'browser';
        $data['children'] = array_values($conduct);
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
    protected function loadBrowser()
    {
        $ret = $this->statService->getStatBrowserDistribution();
        $conduct = array();
        foreach ($ret as $v) {
            $conduct[$v['browser']] = empty($conduct[$v['browser']]) ? 0 : $conduct[$v['browser']];
            $conduct[$v['browser']] += $v['count'];
        }
        $data = array();
        arsort($conduct);
        foreach ($conduct as $key => $value) {
            $d = array();
            $d['label'] = $key;
            $d['data'] = $value;
            //$data[] = array($key, $value);
            $data[] = $d;
        }
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
    protected function loadClientTop()
    {
        $period = empty($_REQUEST['period']) ? 30 : intval($_REQUEST['period']);
        // include
        $startDate = empty($_REQUEST['startDate']) ? date('Y-m-d', time() - 86400 * $period + 86400) : $_REQUEST['startDate'];
        // include
        $endDate = empty($_REQUEST['endDate']) ? date('Y-m-d') : $_REQUEST['endDate'];
        // basic data
        $cond = array();
        $cond['startDate'] = $startDate;
        $cond['endDate'] = $endDate;
        //$ret = StatClientService::clientsCount($cond, 5);
        $ret = $this->statClientService->getClientTop(5, $cond);
        // clients infomation
        $group = array();
        foreach ($ret as $v) {
            $group[] = $v['client_id'];
        }
        $clients = ClientService::readClientsByGroup($group);
        $data = array();
        foreach ($ret as $k => $v) {
            $d = array();
            $d['label'] = $clients[$v['client_id']]['clientName'];
            $d['data'] = $v['num'];
            $data[] = array($clients[$v['client_id']]['clientName'], $v['num']);
        }
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
    protected function loadOnline()
    {
        $online = $this->sessionService->getOnlineUserCount();
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $online);
    }
}
// PHP END
