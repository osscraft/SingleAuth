<?php

namespace Dcux\Api\Action\Account;

use Lay\Advance\Core\Error;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\Api\Kernel\Api;

class Resource extends TokenApi {
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
		$user = $this->getUser();
		if($user){
			$list = new VList();
			$list->list = $user;
			$list->total = count($user);
			$this->success($list);
		} else {
			//$this->failure(200103,$CFG['error'][200103]);	
			throw new Error(Errode::invalid_user(), 200103);
        }
    }
}
// PHP END