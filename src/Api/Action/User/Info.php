<?php

namespace Dcux\Api\Action\User;

use Lay\Advance\Core\Error;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VUser;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\Api\Kernel\Api;

class Info extends TokenApi {
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
		$user = $this->getUser();
		if($user){
			$this->success(VUser::parse($user));
		} else {
			//$this->failure(200103,$CFG['error'][200103]);	
			throw new Error(Errode::invalid_user());
        }
    }
}
// PHP END