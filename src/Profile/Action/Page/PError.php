<?php

namespace Dcux\Profile\Action\Page;

use Dcux\Core\App;
use Dcux\Core\Action;
use Dcux\Core\Errode;

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