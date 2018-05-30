<?php

namespace Dcux\Cli\Action\Fix;

use Dcux\Cli\Kernel\CliAction;
use Dcux\SSO\Service\SessionService;
use Dcux\SSO\Service\StatService;

class Fixdaily extends CliAction {
	protected $statService;
	public function onCreate() {
		parent::onCreate();
		$this->statService = StatService::getInstance();
	}
	public function on() {
		$ret = true;
		$num = 30;
		for ($i=$num; $i > 0; $i--) {
			$date = date('Y-m-d', time() - $i * 86400);
			$ret = $this->fixDailyuser($date);

			if($ret) {
				$this->template->push("code", 0);
				$this->template->push("data", "fixed daily statistics");
			} else {
				$this->template->push("code", 900003);
				$this->template->push("data", "error fixed daily user statistics");
				$this->template->push("date", $date);
				return;
			}

			$ret = $this->fixDailyclient($date);

			if($ret) {
				$this->template->push("code", 0);
				$this->template->push("data", "fixed daily statistics");
			} else {
				$this->template->push("code", 900003);
				$this->template->push("data", "error fixed daily client statistics");
				$this->template->push("date", $date);
				return;
			}
		}
	}
	public function fixDailyuser($date) {
		$per = 1000;
		$offset = 0;
		$count = array();
		do {
			$limit = array($offset, $per);
			$lists = $this->statService->getStatUserDetailDaily($date, $limit);
			foreach ($lists as $k => $v) {
				$uid = $v['username'];
				$count[$uid] = isset($count[$uid]) ? $count[$uid] : 0; 	
				if($v['success'] == 1) {
					$count[$uid] += 1; 
				}
			}
			$offset += $per;
		} while (count($lists) >= $per);

		//update daily user signin count
		$ret = true;
		foreach ($count as $uid => $count) {
			$info = array();
			$info['date'] = $date;
			$info['username'] = $uid;
			$info['count'] = $count;
			if($count) {
				$ret = $ret && $this->statService->addStatUser($info);
			}
		}
		return $ret;
	}
	public function fixDailyclient($date) {
		$per = 1000;
		$offset = 0;
		$count = array();
		do {
			$limit = array($offset, $per);
			$lists = $this->statService->getStatUserDetailDaily($date, $limit);
			foreach ($lists as $k => $v) {
				$cid = $v['clientId'];
				$count[$cid] = isset($count[$cid]) ? $count[$cid] : array('count' => 0, 'countVisit' => 0);
				if($v['success'] == 1) {
					$count[$cid]['count'] += 1;
				}
				$count[$cid]['countVisit'] += 1;
			}
			$offset += $per;
		} while (count($lists) >= $per);

		//update daily clinet signin and visit count
		$ret = true;
		foreach ($count as $cid => $count) {
			$info = array();
			$info['date'] = $date;
			$info['clientId'] = $cid;
			$info['count'] = $count['count'];
			$info['countVisit'] = $count['countVisit'];
			if($count['count']) {
				$ret = $ret && $this->statService->addStatClient($info);
			}
		}
		return $ret;
	}
}