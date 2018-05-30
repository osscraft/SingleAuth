<?php
namespace Dcux\Cli\Action\User;

use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;

use Dcux\Cli\Kernel\CliAction;
use Dcux\SSO\Service\SettingService;
use Dcux\SSO\Service\UserExtensionService;

class Renovate extends CliAction {
	private $settingService;
	private $userExtensionService;
	public function onCreate() {
		parent::onCreate();
        $this->settingService = SettingService::getInstance();
        $this->userExtensionService = UserExtensionService::getInstance();
	}

	public function on() {
		// 检测当前是否已经锁住了任务
		$lock = $this->settingService->get('cron_user_ext_renovate_locked');
		if(!empty($lock)) {
			$this->template->push("code", 0);
			$this->template->push("data", "user extension renovation is locked");
			return true;
		} else {
			// 锁
			$this->settingService->replace(array('k' => 'cron_user_ext_renovate_locked', 'v' => 1));
		}

		$last = $this->settingService->get('cron_user_ext_renovate_last_time');
		// 获取最后更新时间
		if(!empty($last)) {
			$last_time = $last['v'];
		}
		// 开始时间，更新时间节点
		$top_time = date('Y-m-d H:i:s');
		$offset = 0;
		$num = 100;
		do {
			$condtion = array();
			$order = array('time' => 'DESC');
			$limit = array($offset, $num);

			if(empty($last_time)) {
				$condtion['lastLogin'] = array($top_time, '<=');
			} else {
				$condtion['lastLogin.0'] = array($last_time, '>');
				$condtion['lastLogin.1'] = array($top_time, '<=');
			}
			$list = $this->userExtensionService->getQueryList($condtion, $order, $limit);
			//释放
			$free = $this->userExtensionService->freeResult();

			if(empty($list)) {
				// 没有，跳出
				break;
			} else {
				foreach ($list as $k => $current) {
					// 运算浏览器和OS检测
					if(!empty($current['lastUa'])) {
						$uid = $current['uid'];
						$browser = Utility::browser(false, false, $current['lastUa']);
						$os = Utility::os(false, $current['lastUa']);
						$this->userExtensionService->upd($uid, array('lastBrowser' => $browser, 'lastOs' => $os));
					}
				}
			}


			$count = count($list);
			$offset += $num;
		} while ($count >= $num);

		// 更新最后更新时间，及释放锁
		$this->settingService->del('cron_user_ext_renovate_locked');
		$this->settingService->replace(array('k' => 'cron_user_ext_renovate_last_time', 'v' => $top_time));
		$this->template->push("code", 0);
		$this->template->push("data", "done user extension renovation");
	}
}