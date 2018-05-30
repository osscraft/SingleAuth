<?php

namespace Dcux\Cli\Action\Sso;

use Lay\Advance\Core\App;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\SessionService;
use Dcux\SSO\Service\StatService;

class Failure extends CronAction {
	protected $statService;
	public function onCreate() {
		parent::onCreate();
		$this->statService = StatService::getInstance();
	}
	public function on() {
		// 每天统计一次
		$per = 1000;
		$date = date('Y-m-d', time() - 86400);
		$offset = 0;
		$count = array();
		do {
			$limit = array($offset, $per);
			$lists = $this->statService->getStatUserDetailDaily($date, $limit);
			foreach ($lists as $k => $v) {
				$ip = $v['ip'];
				$cid = $v['clientId'];
				$count[$ip] = isset($count[$ip]) ? $count[$ip] : array();
				$count[$ip][$cid] = isset($count[$ip][$cid]) ? $count[$ip][$cid] : 0;
				if($v['success'] == 0) {
					$count[$ip][$cid] += 1; 
				}
			}
			$offset += $per;
		} while (count($lists) >= $per);

		//update failure
		$ret = true;
		foreach ($count as $ip => $v) {
			foreach ($v as $cid => $count) {
				$info = array();
				$info['date'] = $date;
				$info['ip'] = $ip;
				$info['clientId'] = $cid;
				$info['count'] = $count;
				if($count > 0) {
					$ret = $this->statService->addStatFailure($info);
				}
			}
		}

		if($ret) {
			$this->template->push("code", 0);
			$this->template->push("data", "done failure statistics");
		} else {
			$this->template->push("code", 900003);
			$this->template->push("data", "error failure statistics");
		}
		//$this->template->push('code', 0);
	}
}