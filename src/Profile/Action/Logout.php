<?php

namespace Dcux\Profile\Action;

use Dcux\Profile\Kernel\Profile;

class Logout extends Profile {
    public function onGet() {
        global $CFG;
        unset($_SESSION['pr_token']);
        unset($_SESSION['pr_user']);
        $this->template->redirect($CFG['SSO_CALLBACK']);
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END