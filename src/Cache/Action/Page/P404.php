<?php

namespace Dcux\Cache\Action\Page;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;

class P404 extends Action {
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        $out = array ();
        $this->template->header('HTTP/1.1 404 Not Found');
        $this->template->push('code', 404);
        $this->template->file('404.php');
    }
}
// PHP END