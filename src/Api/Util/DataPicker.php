<?php
namespace Dcux\Api\Util;

use Lay\Advance\Util\Logger;

use Dcux\SSO\Service\UserService;
use Dcux\SSO\Service\IdentifyService;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatService;

class DataPicker {
	protected $userService;
	protected $identifyService;
	protected $clientService;
	protected $statService;
	public function __construct() {
		$this->userService = UserService::getInstance();
		$this->identifyService = IdentifyService::getInstance();
		$this->clientService = ClientService::getInstance();
		$this->statService = StatService::getInstance();
	}
	public function pickUser($uid) {
		return $this->identifyService->getUser($uid);
	}
	public function pickUserRole($role) {
		return $this->userService->parseRole($role);
	}
	public function pickClient($clientId) {
		return $this->clientService->getByUnique($clientId, false);
	}
	public function pickClientById($id) {
		return $this->clientService->get($id, false);
	}
	public function pickStatUserDetail($id) {

	}
}