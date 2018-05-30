<?php

namespace Dcux\Admin\Action\Stat;

use Autoloader;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Paging;

use Dcux\Admin\Kernel\MenuPermission;
use Dcux\SSO\Service\StatUserDetailService;
use Dcux\SSO\Service\ClientService;

class Client extends MenuPermission {
	protected $clientService;
    public function cmd() {
        return 'statistics.client';
    }
    public function onCreate() {
    	parent::onCreate();
    	$this->clientService = ClientService::getInstance();
    }
    public function onGet() {
    	$clients = $this->clientService->getClientListAll(array(), array('clientOrderNum' => 'DESC'));
    	$clients = empty($clients) ? array() : $clients;
    	$this->template->push('clients', $clients);
        $this->template->file('stat/clientdate.php');
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END