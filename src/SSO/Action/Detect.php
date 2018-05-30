<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Browscap;
use Lay\Advance\Util\Utility;

use Dcux\SSO\Kernel\Action;
use Dcux\SSO\Service\ClientService;

class Detect extends Action {
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
    	$browscap = new Browscap();
        $out = $browscap->getBrowser();
        $this->template->push('os', Utility::os());
        $this->template->push('browser', Utility::browser());
        $this->template->push('server', $_SERVER);
        $this->template->push($out);
    }
}
// PHP END