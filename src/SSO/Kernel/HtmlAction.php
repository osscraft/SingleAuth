<?php

namespace Dcux\SSO\Kernel;

use Lay\Advance\Util\Logger;

use Dcux\SSO\Kernel\Action;

abstract class HtmlAction extends Action {
    public function onRender() {
        global $CFG;
        $this->template->push('CFG', $CFG);
        $this->template->push('LANG', $CFG['LANG']);
        parent::onRender();
    }
}