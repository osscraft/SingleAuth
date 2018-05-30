<?php
namespace Dcux\Api\Action;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VClient;
use Dcux\Api\Data\VStatClient;
use Dcux\Api\Kernel\Api;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatClientService;
use stdClass;

class Index extends Api {
	public function onCreate() {
		parent::onCreate();
		$this->clientService = ClientService::getInstance();
		$this->statClientService = StatClientService::getInstance();
	}
	public function onGet() {
		$list = new VList();
		$arr = array(new VObject(), 1);
		
		$arr[] = VClient::parse($this->clientService->getByUnique('ufsso_profile'));
		$arr[] = VStatClient::parse($this->statClientService->get(213));
		$arr[] = Security::generateSid('liaiyong');
		$arr[] = VStatClient::parse($this->statClientService->get(214));
		$arr[] = Security::getUidFromSid('mPWsTPVEh75pDQai93VLOh9inLCRwPiz7g==');
		$std = new stdClass;
		$arr[] = empty($std);
		$list->list = $arr;
		$list->total = count($arr);

		/*$array = $this->clientService->getAll(array('id' => 'DESC'), array(10));
		$total = $this->clientService->count();
		$list = VClient::parseList($array, $total);*/
		//echo '<pre>';print_r($list);exit;
		//$this->response->data = $list;
		//$this->response->success();
		//$this->success();
		$this->success($list);
	}
	public function onPost() {
		$this->onGet();
	}
}
// PHP END