<?php
namespace Dcux\Core;

use Dcux\Core\App;
use Dcux\Core\Singleton;
use Dcux\Core\Epibolic;
use Dcux\Util\Logger;

abstract class Epiboly extends Singleton implements Epibolic
{
    protected function __construct()
    {
        parent::__construct();
        $this->initialize();
    }

    abstract public function initialize();
}
// PHP END
