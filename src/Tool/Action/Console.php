<?php
namespace Dcux\Tool\Action;

use Dcux\Tool\Kernel\ToolAction;

class Console extends ToolAction {
	public function onGet() {

		$this->template->file('console.php');
	}
}

// PHP END