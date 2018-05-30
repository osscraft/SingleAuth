<?php

namespace Dcux\Cli\Action;

use Lay\Advance\Core\App;
use Dcux\Cli\Kernel\CliAction;

class Index extends CliAction {
    public function on() {
        print_r("expression\n");
    }
}
// PHP END