<?php

namespace Dcux\Portal\Action\Page;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;
use Lay\Advance\Core\Errode;

class PError extends Action
{
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $error = Errode::__lastErrode();
        $this->template->push('code', $error->code);
        $this->template->push('data', $error->message);
        $this->template->file('error.php');
    }
}
// PHP END
