<?php


namespace Dcux\Admin\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;

use Dcux\Admin\Kernel\Permission;

abstract class AjaxPermission extends Permission {
    // override onRender
    public function cmd() {
        return '';
    }
}

// PHP END