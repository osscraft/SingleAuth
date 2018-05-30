<?php

namespace Dcux\Portal\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;

use Dcux\Portal\Kernel\PAction;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatService;
use Dcux\SSO\Service\SessionService;
use Dcux\Portal\Kernel\MultiDateAction;

class Top extends MultiDateAction {
    protected $clientService;
    protected $userManager;
    protected $statClientService;
    protected $statService;
    protected $sessionService;
    public function onCreate() {
        parent::onCreate();
        $this->clientService = ClientService::getInstance();
        $this->userService = UserService::getInstance();
        $this->statClientService = StatClientService::getInstance();
        $this->statService = StatService::getInstance();
        $this->sessionService = SessionService::getInstance();
    }
    public function onGet() {
        //$this->template->push($stat);
        $this->onPost();
    }
    public function onPost() {
        $key = empty($_REQUEST['key']) ? 'client_top' : $_REQUEST['key'];
        if($key == 'client_top'){
            $this->loadClientTop();
        } else if($key == 'user_top'){
            $this->loadUserTop();
        } else if($key == 'browser_top'){
            $this->loadBrowserTop();
        } else if($key == 'browser_d3'){
            $this->loadBrowserD3();
        }
    }

    protected function loadBrowserTop() {
        $num = empty($_REQUEST['num']) ? 8 : $_REQUEST['num'];
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
        $data = array_slice($data, 0, $num);
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
    protected function loadBrowserD3() {
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
    protected function loadClientTop() {
        list($startDate, $endDate, $period, $type) = $this->detectDatetime();
        // basic data
        $cond = array();
        $cond['startDate'] = $startDate;
        $cond['endDate'] = $endDate;
        $num = empty($_REQUEST['num']) ? 5 : $_REQUEST['num'];
        if($startDate == date('Y-m-d')) {
            $ret = $this->statService->getStatClientTopToday($num, $cond);
        } else {
            $ret = $this->statService->getStatClientTop($num, $cond);
        }
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
            $d['data'] = intval($v['count']);
            if($d['data']!=0){
                $data[] = array($d['data'], $d['label']);
            }
        }
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
    protected function loadUserTop() {
        list($startDate, $endDate, $period, $type) = $this->detectDatetime();
        // basic data
        $cond = array();
        $cond['startDate'] = $startDate;
        $cond['endDate'] = $endDate;
        $num = empty($_REQUEST['num']) ? 5 : $_REQUEST['num'];
        if($startDate == date('Y-m-d')) {
            $ret = $this->statService->getStatUserTopToday($num, $cond);
        } else {
            $ret = $this->statService->getStatUserTop($num, $cond);
        }

        $data = array();
        foreach ($ret as $k => $v) {
            $d = array();
            $d['label'] = $v['username'];
            $d['data'] = intval($v['count']);
            if($d['data']!=0){
                $data[] = array($d['label'], $d['data']);
            }
        }
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
}
// PHP END