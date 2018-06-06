<?php

namespace Dcux\Cli\Action\Sso;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\SessionService;
use Dcux\SSO\Service\StatService;

class Dailyuser extends CronAction
{
    protected $statService;
    public function onCreate()
    {
        parent::onCreate();
        $this->statService = StatService::getInstance();
    }
    public function on()
    {
        // daily
        $per = 1000;
        $date = date('Y-m-d');
        $offset = 0;
        $count = array();
        do {
            $limit = array($offset, $per);
            $lists = $this->statService->getStatUserDetailDaily($date, $limit);
            foreach ($lists as $k => $v) {
                $uid = $v['username'];
                $count[$uid] = isset($count[$uid]) ? $count[$uid] : 0;
                if ($v['success'] == 1) {
                    $count[$uid] += 1;
                }
            }
            $offset += $per;
        } while (count($lists) >= $per);

        //update daily user signin count
        $ret = true;
        foreach ($count as $uid => $count) {
            $info = array();
            $info['date'] = $date;
            $info['username'] = $uid;
            $info['count'] = $count;
            if ($count) {
                $ret = $this->statService->addStatUser($info);
            }
        }

        if ($ret) {
            $this->template->push("code", 0);
            $this->template->push("data", "done daily user statistics");
        } else {
            $this->template->push("code", 900003);
            $this->template->push("data", "error daily user statistics");
        }
        //$this->template->push('code', 0);
    }
}
