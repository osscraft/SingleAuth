<?php

namespace Dcux\Cli\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;

use Dcux\Cli\Kernel\App;

abstract class CliAction extends Action
{
    abstract public function on();
    /**
     * do GET
     */
    public function onGet()
    {
        $this->on();
    }
}

// PHP END
