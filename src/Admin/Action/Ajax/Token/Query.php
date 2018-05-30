<?php

namespace Dcux\Admin\Action\Ajax\Token;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\SSO\Service\OAuth2TokenService;

class Query extends AjaxPermission {
	protected $oauth2TokenService;
	public function onCreate() {
		$this->oauth2TokenService = OAuth2TokenService::getInstance();
	}
    public function onGet() {
        $tokens = $this->oauth2TokenService->getTokenListAll();
        $this->template->push('data', $tokens);
    }
    public function onPost() {
    	$this->onGet();
    }
}
// PHP END