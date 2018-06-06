<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;

use Dcux\SSO\Kernel\HtmlAction;

class Log extends HtmlAction
{
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $out = array();
        $this->template->push($out);
        $this->template->file('log.php');
    }
}
// PHP END
