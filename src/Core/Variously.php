<?php
namespace Dcux\Core;

use Dcux\Core\Customizable;

interface Variously extends Customizable {
	public function setTheme($theme);
	public function getTheme();
}
// PHP END