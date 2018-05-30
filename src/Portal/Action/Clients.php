<?php

namespace Dcux\Portal\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;

use Dcux\Portal\Kernel\PAction;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\ClientService;

class Clients extends PAction{
	protected $clientService;
	public function onCreate() {
        parent::onCreate();
		$this->clientService = ClientService::getInstance();
    }
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
		Global $CFG;
		$condition = array();
		if(!empty($CFG['UNSHOW_CLIENTS'])){
			$condition['clientId'] = array($CFG['UNSHOW_CLIENTS'], 'NOT IN');
		}
        $clients = $this->clientService->getClientListAll($condition, array('clientOrderNum' => 'DESC'));
		/*$clients = empty($clients) ? array() : $clients;
		$clientArr = array();
		foreach($clients as $client){
			$clientArr[] = array('clientName'=>$client['clientName'],'clientId'=>$client['clientId']);
		}
    	$this->template->push('data', $clientArr);*/
		$this->template->push('data',$clients);
    }
}
// PHP END