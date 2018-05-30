<?php

namespace Dcux\Cli\Kernel;

use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;

use Dcux\Cli\Kernel\App;
use Dcux\Cli\Kernel\CliAction;

abstract class CronAction extends CliAction {
    public function onGet() {
		global $CFG;
		if(empty($CFG['cron_open'])) {
			$this->template->push("code", 900000);
			$this->template->push("data", "disabled cronjob config");
		} else {
			parent::onGet();
		}
    }
}

// PHP END