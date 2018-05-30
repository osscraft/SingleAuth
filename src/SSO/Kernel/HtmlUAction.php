<?php

namespace Dcux\SSO\Kernel;

use Lay\Advance\Util\Logger;

use Dcux\SSO\Kernel\UAction;

abstract class HtmlUAction extends UAction {
    public function onRender() {
        global $CFG;
        $this->template->push('CFG', $CFG);
        $this->template->push('LANG', $CFG['LANG']);
        parent::onRender();
    }
}