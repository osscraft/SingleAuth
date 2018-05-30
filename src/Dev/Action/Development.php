<?php

namespace Dcux\Dev\Action;

use Dcux\Dev\Kernel\DAction;

class Development extends DAction {
    public function onGet() {
        global $CFG;
        $this->template->file('development.php');
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END