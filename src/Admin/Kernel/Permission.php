<?php


namespace Dcux\Admin\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;

use Dcux\Admin\Kernel\AAction;

abstract class Permission extends AAction {
	public function onCreate() {
		parent::onCreate();
        if (empty($_SESSION['user']) || $_SESSION['user']['isAdmin'] < 1) {
            $this->template->redirect('/admin/index.php', array(), false);
        }
	}
}