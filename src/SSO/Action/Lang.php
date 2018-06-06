<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;

use Dcux\SSO\Kernel\Action;

class Lang extends Action
{
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        global $CFG;
        ksort($CFG['LANG']);
        $this->template->push($CFG['LANG']);
    }
}
// PHP END
