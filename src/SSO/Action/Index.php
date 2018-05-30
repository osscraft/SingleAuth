<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Errode;

use Dcux\SSO\Kernel\HtmlAction;
use Dcux\SSO\Service\ClientService;

class Index extends HtmlAction {
    public function onCreate() {
    	parent::onCreate();
    	$this->clientService = ClientService::getInstance();
    }
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
    	global $CFG;
		$condition = array();
		if(!empty($CFG['UNSHOW_CLIENTS'])){
			$condition['clientId'] = array($CFG['UNSHOW_CLIENTS'], 'NOT IN');
		}
        $clients = $this->clientService->getClientListAll($condition, array('clientOrderNum' => 'DESC'));
    	$clients = empty($clients) ? array() : $clients;
    	$this->template->push('clients', $clients);
        //$this->template->push($out);
        $this->template->file('index.php');
    }
}
// PHP END