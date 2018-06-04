<?php

namespace Lay\Advance\Core;

use Lay\Advance\Core\Customizable;

abstract class Customizer extends Singleton {
	/**
	 * @return Epiboly
	 */
	public abstract function epiboly();
	public function customize(Customizable $customizable) {
		$epiboly = $this->epiboly();
		$epiboly->receive($customizable);
		$epiboly->produce();
		return $epiboly->delivery();
	}
}

// PHP END