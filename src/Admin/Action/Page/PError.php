<?php

namespace Dcux\Admin\Action\Page;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Errode;

use Dcux\Admin\Kernel\Action;

class PError extends Action {
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        $error = Errode::__lastErrode();
        $this->template->push('code', $error->code);
        $this->template->push('data', $error->message);
        $this->template->file('error.php');
    }
}
// PHP END