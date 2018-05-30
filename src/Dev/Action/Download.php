<?php

namespace Dcux\Dev\Action;

use Dcux\Dev\Kernel\DAction;

class Download extends DAction {
    public function onGet() {
        global $CFG;
        $this->template->push('PROJECT_PATH', $CFG['project_path']);
        $this->template->file('download.php');
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END