<?php
namespace Lay\Advance\Core;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Singleton;
use Lay\Advance\Core\Epibolic;
use Lay\Advance\Util\Logger;

abstract class Epiboly extends Singleton implements Epibolic {
    protected function __construct() {
        parent::__construct();
        $this->initialize();
    }

    public abstract function initialize();
}
// PHP END