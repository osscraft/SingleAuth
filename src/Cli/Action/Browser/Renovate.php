<?php
namespace Dcux\Cli\Action\Browser;

use Lay\Advance\Util\Utility;

use Dcux\Cli\Kernel\CliAction;
use Dcux\SSO\Service\SettingService;
use Dcux\SSO\Service\StatUserDetailService;

class Renovate extends CliAction
{
    private $settingService;
    private $statUserDetailService;
    public function onCreate()
    {
        parent::onCreate();
        $this->settingService = SettingService::getInstance();
        $this->statUserDetailService = StatUserDetailService::getInstance();
    }

    public function on()
    {
        // 检测当前是否已经锁住了任务
        $lock = $this->settingService->get('cron_browser_renovate_locked');
        if (!empty($lock)) {
            $this->template->push("code", 0);
            $this->template->push("data", "browser renovation is locked");
            return true;
        } else {
            // 锁
            $this->settingService->replace(array('k' => 'cron_browser_renovate_locked', 'v' => 1));
        }

        $last = $this->settingService->get('cron_browser_renovate_last_time');
        // 获取最后更新时间
        if (!empty($last)) {
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

            if (empty($last_time)) {
                $condtion['time'] = array($top_time, '<=');
            } else {
                $condtion['time.0'] = array($last_time, '>');
                $condtion['time.1'] = array($top_time, '<=');
            }
            $list = $this->statUserDetailService->getQueryList($condtion, $order, $limit);
            //释放
            $free = $this->statUserDetailService->freeResult();

            if (empty($list)) {
                // 没有，跳出
                break;
            } else {
                foreach ($list as $k => $current) {
                    //$uid = $current['username'];// stat_user_detail表中的username是uid
                    // 运算浏览器和OS检测
                    if (!empty($current['ua'])) {
                        $id = $current['id'];
                        $browser = Utility::browser(false, false, $current['ua']);
                        $os = Utility::os(false, $current['ua']);
                        $this->statUserDetailService->upd($id, array('browser' => $browser, 'os' => $os));
                    }
                }
            }


            $count = count($list);
            $offset += $num;
        } while ($count >= $num);

        // 更新最后更新时间，及释放锁
        $this->settingService->del('cron_browser_renovate_locked');
        $this->settingService->replace(array('k' => 'cron_browser_renovate_last_time', 'v' => $top_time));
        $this->template->push("code", 0);
        $this->template->push("data", "done browser renovation");
    }
}
