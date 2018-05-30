<?php

namespace Dcux\Admin\Action\Setting;

use Dcux\Admin\Kernel\MenuPermission;
use Dcux\Admin\Action\Setting;

class Lists extends MenuPermission {
    public function cmd() {
        return 'setting';
    }
    public function onCreate() {
        parent::onCreate();
        $t = $this->template->getTheme();
        if(empty($t) || $t == 'default') {
            //直接跳至
            $this->template->redirect('/admin/setting.php', array(), false);
        }
    }
    public function onGet() {
        //print_r("expression");exit;
        //parent::onGet();
        $this->template->file('setting/list.php');
    }
}
// PHP END