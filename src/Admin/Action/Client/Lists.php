<?php

namespace Dcux\Admin\Action\Client;

use Dcux\Admin\Kernel\MenuPermission;
use Dcux\Admin\Action\Client;

class Lists extends MenuPermission {
    public function cmd() {
        return 'client';
    }
    public function onCreate() {
        parent::onCreate();
        $t = $this->template->getTheme();
        if(empty($t) || $t == 'default') {
            //直接跳至
            $this->template->redirect('/admin/client.php', array(), false);
        }
    }
    public function onGet() {
        //print_r("expression");exit;
        //parent::onGet();
        $this->template->file('client/list.php');
    }
}
// PHP END