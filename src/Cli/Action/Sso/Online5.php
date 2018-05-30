<?php

namespace Dcux\Cli\Action\Sso;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\SessionService;
use Dcux\SSO\Service\StatService;

class Online5 extends CronAction {
	protected $sessionService;
	public function onCreate() {
		parent::onCreate();
		$this->sessionService = SessionService::getInstance();
		$this->statService = StatService::getInstance();
	}
	public function on() {
		global $CFG;
		$minute = date('i');
		// 5分钟一次
		if($minute % 5 == 0) {
			$arr = array();
			$arr['time'] = date('Y-m-d H:i:s', floor($time + $gap * $i));
			$arr['count'] = $this->sessionService->getOnlineUserCount();
			//if(!empty($arr['count'])) {
			$ret = $this->statService->addStatOnline($arr);
			//}
			//$counts[$i] = $arr;
		} else {
			$ret = true;
		}
		if($ret) {
			$this->template->push("code", 0);
			$this->template->push("data", "completed online 5 statistics");
		} else {
			$this->template->push("code", 900003);
			$this->template->push("data", "error online 5 statistics");
		}
		//$this->template->push('code', 0);
	}
}