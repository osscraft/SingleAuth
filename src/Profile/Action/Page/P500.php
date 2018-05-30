<?php

namespace Dcux\Profile\Action\Page;

use Dcux\Core\App;
use Dcux\Core\Action;

class P500 extends Action {
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        $out = array ();
        $this->template->push('code', 500);
        $this->template->file('500.php');
    }
}
// PHP END