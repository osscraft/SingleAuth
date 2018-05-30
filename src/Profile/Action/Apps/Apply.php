<?php

namespace Dcux\Profile\Action\Apps;

use Dcux\Profile\Kernel\Permission;

class Apply extends Permission {
    public function onGet() {
        $this->template->file('apply.php');
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END