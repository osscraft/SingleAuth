<?php

namespace Dcux\Admin\Action\Page;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;

class P500 extends Action
{
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $out = array();
        $this->template->push('code', 500);
        $this->template->file('500.php');
    }
}
// PHP END
