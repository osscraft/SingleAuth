<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;

use Dcux\SSO\Kernel\UAction;

class Keep extends UAction
{
    public function onGet()
    {
        global $CFG;
        if (empty($_SESSION['uid'])) {
            $this->errorResponse($CFG['LANG']['ERROR']['NOT_LOGGED_IN']);
        } else {
            $this->template->push('code', 0);
        }
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
