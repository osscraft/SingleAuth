<?php

namespace Dcux\Admin\Action\User;

use Dcux\Admin\Kernel\AAction;
use Dcux\Admin\Kernel\MenuPermission;
use Dcux\Admin\Action\Client;

class Token extends MenuPermission {
    public function cmd() {
        return 'token';
    }
    public function onGet() {
        $this->template->file('user/token.php');
    }
}
// PHP END