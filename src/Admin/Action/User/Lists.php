<?php

namespace Dcux\Admin\Action\User;

use Dcux\Admin\Kernel\MenuPermission;
use Dcux\Admin\Action\User;

class Lists extends MenuPermission
{
    public function cmd()
    {
        return 'user';
    }
    public function onCreate()
    {
        parent::onCreate();
        $t = $this->template->getTheme();
        if (empty($t) || $t == 'default') {
            //直接跳至
            $this->template->redirect('/admin/user.php', array(), false);
        }
    }
    public function onGet()
    {
        //print_r("expression");exit;
        //parent::onGet();
        $this->template->file('user/list.php');
    }
}
// PHP END
