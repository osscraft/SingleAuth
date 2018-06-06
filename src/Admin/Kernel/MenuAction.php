<?php


namespace Dcux\Admin\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;

use Dcux\Admin\Kernel\AAction;

abstract class MenuAction extends AAction
{
    // override onRender
    public function onRender()
    {
        global $CFG;
        // add menu
        $this->template->push('menu', $this->menu());
        $this->template->push('chain', $this->chain());
        $this->template->push('CFG', $CFG);
        $this->template->push('LANG', $CFG['LANG']);
        parent::onRender();
    }
}

// PHP END
