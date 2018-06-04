<?php
namespace Lay\Advance\Core;

use Lay\Advance\Core\Action;
use Lay\Advance\Util\Logger;

class Trustee extends Action {
	public function onGet() {
		throw Errode::__lastErrode()->error();
	}
	public function onPost() {
		$this->onGet();
	}
}
// PHP END