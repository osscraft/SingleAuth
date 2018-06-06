<?php

namespace Dcux\Cli\Action\Sync;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;

use Dcux\Cli\Kernel\CronAction;
use Dcux\Cli\Service\TransferService;

class Inituser extends CronAction
{
    protected $transferService;
    protected $trUserService;
    public function onCreate()
    {
        parent::onCreate();
        $this->transferService = TransferService::getInstance();
    }
    public function on()
    {
        // per 5 minutes
        $minute = date('i');

        if ($minute % 5 == 0) {
            $ret = $this->transferService->sync();
        } else {
            $ret = true;
        }
        if ($ret) {
            $this->template->push("code", 0);
            $this->template->push("data", "done sync user");
        } else {
            $this->template->push("code", 900001);
            $this->template->push("data", "sync user error");
        }
        //$this->template->push('code', 0);
    }
}
