<?php

namespace Dcux\Cli\Action\Sso;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\SessionService;

class Clean extends CronAction
{
    protected $sessionService;
    public function onCreate()
    {
        parent::onCreate();
        $this->sessionService = SessionService::getInstance();
    }
    public function on()
    {
        // total 60s, per 5s
        for ($i=0; $i < 12; $i++) {
            $ret = $this->sessionService->clean();
            sleep(5);
        }
        if ($ret) {
            $this->template->push("code", 0);
            $this->template->push("data", "cleaned session");
        } else {
            $this->template->push("code", 900001);
            $this->template->push("data", "not cleaned session");
        }
        //$this->template->push('code', 0);
    }
}
