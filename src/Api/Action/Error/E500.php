<?php
namespace Dcux\Api\Action\Error;

use Lay\Advance\Core\Errode;
use Lay\Advance\Util\Logger;

use Dcux\Api\Data\VList;
use Dcux\Api\Kernel\Api;

class E500 extends Api {
	public function onGet() {
		$this->failure(Errode::internal_server_error());
	}
	public function onPost() {
		$this->onGet();
	}
}
// PHP END