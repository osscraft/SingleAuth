<?php

namespace Dcux\ChangePass\Action;

use Dcux\ChangePass\Kernel\AAction;

class Logout extends AAction {
    public function onGet() {
        global $CFG;
        unset($_SESSION['cp_token']);
        unset($_SESSION['cp_user']);
        $this->template->redirect($CFG['SSO_CALLBACK']);
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END