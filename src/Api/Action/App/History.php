<?php
namespace Dcux\Api\Action\App;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VStatClient;
use Dcux\Api\Kernel\SApi;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatClientService;

use Respect\Validation\Validator;

// @see http://sso.project.dcux.com/api/app/history?token=7083f0dd3550aaf73e0c027357b48400&cid=ufsso_dcux_portal

class History extends TokenApi {
	public function onCreate() {
		parent::onCreate();
		$this->clientService = ClientService::getInstance();
		$this->statClientService = StatClientService::getInstance();
	}
	public function onGet() {
		$arr = array();
		$order = $this->getOrderDetail(array('id' => 'DESC'));
		$limit = $this->getLimit();
		$sincer = $this->getSincer();
		$cid = $this->params['cid'];
		$condition  = array();
		$condition['clientId'] = $cid;
		if(!empty($sincer)) {
			$condition['date'] = array($sincer, '<=');
		}

		$exsits = $this->clientService->getByUnique($cid);
		if(!empty($exsits)) {
			$total = $this->statClientService->count($condition);
			$list = $this->statClientService->getConditionList($condition, $order, $limit);
			$this->setDefaultSincer($this->firstDate($list));// before genSince;
			$vscl = VStatClient::parseList($list, $total, $this->genHasNext($total), $this->genSince());
			$this->success($vscl);
		} else {
			$this->failure(Errode::client_not_exists());
		}

	}
	protected function firstDate($list) {
		reset($list);
		$first = current($list);
		return empty($first) ? false : $first['date'];
	}
	public function onPost() {
		$this->onGet();
	}
	protected function params() {
		return array(
			'type' => array('default' => 1),
			'cid' => array(
				'validator' => array(Validator::notEmpty(), Validator::equals($this->getClientId()))
			),
			'num' => array(
				'validator' => array(Validator::notEmpty(), Validator::max($this->max_num, true)),
				'default' => $this->def_num
			)
		);
	}
}
// PHP END