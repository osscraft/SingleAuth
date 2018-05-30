<?php
namespace Dcux\Api\Action\Error;

use Lay\Advance\Core\Errode;
use Lay\Advance\Util\Logger;

use Dcux\Api\Data\VList;
use Dcux\Api\Kernel\Api;

class Error extends Api {
	public function onGet() {
		$this->failure(Errode::__lastErrode());
	}
	public function onPost() {
		$this->onGet();
	}
}
// PHP END