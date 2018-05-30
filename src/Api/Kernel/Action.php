<?php

namespace Dcux\Api\Kernel;

use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;

abstract class Action extends \Lay\Advance\Core\Action {
    // overide
    public function onRender() {
        // render
        header('X-Powered-By: dcux');
        $rep = $this->request->getExtension();
        switch ($rep) {
            case 'xml' :
                $this->template->xml();
                break;
            case 'csv' :
                $this->template->csv();
                break;
            case '' :
            case 'json' :
            default:
                $this->template->json();
                break;
        }
    }
}