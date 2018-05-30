<?php

namespace Dcux\Cli\Action\Server;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\QrCodeService;

class Clean extends CronAction {
    protected $sessionService;
    public function onCreate() {
        parent::onCreate();
        $this->qrCodeService = QrCodeService::getInstance();
    }
    public function on() {
        // total 60s, per 5s 
        for ($i=0; $i < 12; $i++) {
            $ret = $this->qrCodeService->clean();
            sleep(5);
        }
        if($ret) {
            $this->template->push("code", 0);
            $this->template->push("data", "cleaned qrcode");
        } else {
            $this->template->push("code", 900001);
            $this->template->push("data", "not cleaned qrcode");
        }
        //$this->template->push('code', 0);
    }
}