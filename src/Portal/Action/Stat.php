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

class Stat extends PAction
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
        $key = empty($_REQUEST['key']) ? false : $_REQUEST['key'];
        if (empty($key)) {
            if (empty($_SESSION['uid'])) {
                $this->loadDefaultStat();
            } elseif ($_SESSION['role'] == '教师') {
                $this->loadTeacherStat();
            } elseif ($_SESSION['role'] == '学生') {
                $this->loadStudentStat();
            } elseif ($_SESSION['role'] == '其他') {
                $this->loadOtherStat();
            }
            // SSO管理中的用户
            if (!empty($_SESSION['user']) && !empty($_SESSION['user']['isAdmin'])) {
                $this->loadAdminStat();
            }
        } elseif ($key == 'client_top') {
            $this->loadClientTop();
        } elseif ($key == 'browser_top') {
            $this->loadBrowser();
        } elseif ($key == 'online') {
            $this->loadOnline();
        }
        //$stat['sid'] = Security::generateSessionToken($user);
        //$stat['user'] = Security::getInfoFromSessionToken($stat['sid']);
        //$stat['online'] = UserService::getInstance()->getOnlineUserCount();
        //$stat['clients'] = ClientService::getInstance()->updateClientOrderNum();
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
        $ret = StatClientService::clientsCount($cond, 5);
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
