<?php

namespace Dcux\Tool\Kernel;

use Dcux\SSO\Kernel\Action;
use Lay\Advance\Core\Configuration;
use Dcux\SSO\Kernel\SAction;

abstract class ToolAction extends Action {
    // overide
    protected function initTheme() {
        global $CFG;
        // init template dir
        $this->template->directory(\Lay\Advance\Core\App::$_docpath . DIRECTORY_SEPARATOR . 'tool');
        $this->template->theme(empty($CFG['theme']['tool']) ? 'default' : $CFG['theme']['tool']);
    }
}