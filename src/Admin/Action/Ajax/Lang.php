<?php

namespace Dcux\Admin\Action\Ajax;

use Lay\Advance\Core\App;
use Dcux\Admin\Kernel\AjaxPermission;

class Lang extends AjaxPermission {
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        global $CFG;
        ksort($CFG['LANG']);
        $this->template->push($CFG['LANG']);
    }
}
// PHP END