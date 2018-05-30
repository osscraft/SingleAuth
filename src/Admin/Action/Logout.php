<?php

namespace Dcux\Admin\Action;

use Dcux\Admin\Kernel\AAction;

class Logout extends AAction {
    public function cmd() {
        return 'logout';
    }
    public function onGet() {
        global $CFG;
        unset($_SESSION['token']);
        unset($_SESSION['user']);
        $this->template->redirect($CFG['SSO_CALLBACK']);
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END