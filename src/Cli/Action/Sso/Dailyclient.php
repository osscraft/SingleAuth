<?php

namespace Dcux\Cli\Action\Sso;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\SessionService;
use Dcux\SSO\Service\StatService;

class Dailyclient extends CronAction
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
                $cid = $v['clientId'];
                $count[$cid] = isset($count[$cid]) ? $count[$cid] : array('count' => 0, 'countVisit' => 0);
                if ($v['success'] == 1) {
                    $count[$cid]['count'] += 1;
                }
                $count[$cid]['countVisit'] += 1;
            }
            $offset += $per;
        } while (count($lists) >= $per);

        //update daily clinet signin and visit count
        $ret = true;
        foreach ($count as $cid => $count) {
            $info = array();
            $info['date'] = $date;
            $info['clientId'] = $cid;
            $info['count'] = $count['count'];
            $info['countVisit'] = $count['countVisit'];
            if ($count['count']) {
                $ret = $this->statService->addStatClient($info);
            }
        }

        if ($ret) {
            $this->template->push("code", 0);
            $this->template->push("data", "done daily client statistics");
        } else {
            $this->template->push("code", 900003);
            $this->template->push("data", "error daily client statistics");
        }
        //$this->template->push('code', 0);
    }
}
