<?php

namespace Dcux\Profile\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Dcux\Profile\Kernel\Profile;

abstract class Permission extends Profile {
    public function onCreate() {
        parent::onCreate();
        if (empty($_SESSION['pr_user'])) {
            $this->template->redirect('/profile/index.php', array(), false);
        }
    }
}