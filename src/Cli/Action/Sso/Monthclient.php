<?php

namespace Dcux\Cli\Action\Sso;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatClientService;

class Monthclient extends CronAction {
	protected $statService;
	protected $clientService;
	protected $statClientService;
	public function onCreate() {
		parent::onCreate();
		$this->clientService = ClientService::getInstance();
		$this->statClientService = StatClientService::getInstance();
	}
	public function on() {
		// month
		$clientStats = $this->getClientStat();
		$clientIds = array();
		foreach($clientStats as $clientStat){
			$clientIds[] = $clientStat['client_id'];
		}
		$clients = $this->clientService->getClientListByUnique($clientIds);
		$ret = '';
		foreach($clientStats as $clientStat){
			$clientId = $clientStat['client_id'];
			$count = $clientStat['count'];
			$id = $clients[$clientId]['id'];
			$ret = $this->clientService->upd($id,array('clientOrderNum'=>$count));
			if(empty($ret)){
				break;
			}
		}
		if($ret) {
			$this->template->push("code", 0);
			$this->template->push("data", "done month client clientOrderNum");
		} else {
			$this->template->push("code", 900001);
			$this->template->push("data", "error month client clientOrderNum");
		}
	}
	protected function getClientStat(){
		$date = array();
		$date['endDate'] = date('Y-m-d', time() - 86400);
		$date['startDate'] = date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))); 
		$count = $this->statClientService->counts(array());
		$ret = $this->statClientService->getClientTop($count,$date);
		return $ret;
	} 
	
}