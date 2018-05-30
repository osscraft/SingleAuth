<?php
namespace Dcux\Api\Action\Error;

use Lay\Advance\Core\Errode;
use Lay\Advance\Util\Logger;

use Dcux\Api\Data\VList;
use Dcux\Api\Kernel\Api;

class E404 extends Api {
	public function onGet() {
		$this->failure(Errode::api_not_exists());
	}
	public function onPost() {
		$this->onGet();
	}
}
// PHP END