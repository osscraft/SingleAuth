<?php

namespace Dcux\Cli\Action\Sso;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\SessionService;
use Dcux\SSO\Service\StatService;

class Online extends CronAction
{
    protected $sessionService;
    public function onCreate()
    {
        parent::onCreate();
        $this->sessionService = SessionService::getInstance();
        $this->statService = StatService::getInstance();
    }
    public function on()
    {
        global $CFG;
        $time = strtotime(date('Y-m-d H:i:00'));
        $counts = array();
        $ret = true;
        $gap = empty($CFG['cli_sso_online_gap']) || $CFG['cli_sso_online_gap'] < 5 ? 5 : $CFG['cli_sso_online_gap'];
        $frequency = ceil(60/$gap);
        $gap = 60/$frequency;// new gap
        // total 60s, per 5s
        for ($i=0; $i < $frequency; $i++) {
            $arr = array();
            $arr['time'] = date('Y-m-d H:i:s', floor($time + $gap * $i));
            $arr['count'] = $this->sessionService->getOnlineUserCount();
            //if(!empty($arr['count'])) {
            $ret = $this->statService->addStatOnline($arr);
            //}
            $counts[$i] = $arr;
            if ($i < $frequency - 1) {
                usleep($gap * 1000000);
            }
        }
        if ($ret) {
            $this->template->push("code", 0);
            $this->template->push("data", "completed online statistics");
        } else {
            $this->template->push("code", 900003);
            $this->template->push("data", "error online statistics");
        }
        //$this->template->push('code', 0);
    }
}
