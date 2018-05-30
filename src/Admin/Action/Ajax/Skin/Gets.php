<?php

namespace Dcux\Admin\Action\Ajax\Skin;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Skin;

class Gets extends AjaxPermission {
    public function onGet() {
        $data = array();
		$k=$_REQUEST['k'];
		$param=explode('.',$k);
		global $CFG;
		$data['themes'] = $CFG['themes'][$param[1]];
        $this->template->push('data', $data);
    }
    public function onPost() {
    	$this->onGet();
    }
}
// PHP END