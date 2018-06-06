<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Core\Action;
use Lay\Advance\DB\Uniqueness;

use Dcux\SSO\Model\StatClient;
use Dcux\SSO\Model\StatUser;
use Dcux\SSO\Model\StatUserDetail;
use Dcux\SSO\Model\StatBrowser;
use Dcux\SSO\Model\StatFailure;
use Dcux\SSO\Model\StatOnline;

class StatService extends Service
{
    private $statClient;
    private $statUser;
    private $statUserDetail;
    private $statBrowser;
    private $statFailure;
    private $statOnline;
    public function model()
    {
        return StatUserDetail::getInstance();
    }
    protected function __construct()
    {
        parent::__construct();
        $this->statClient = StatClient::getInstance();
        $this->statUser = StatUser::getInstance();
        $this->statUserDetail = StatUserDetail::getInstance();
        $this->statBrowser = StatBrowser::getInstance();
        $this->statFailure = StatFailure::getInstance();
        $this->statOnline = StatOnline::getInstance();
    }

    public function addStatOnline($args = array())
    {
        return $this->statOnline->add($args);
    }
    public function addStatFailure($args = array())
    {
        return $this->statFailure->replace($args);
    }
    public function addStatUser($args = array())
    {
        return $this->statUser->replace($args);
    }
    public function addStatClient($args = array())
    {
        return $this->statClient->replace($args);
    }
    /**
     * 增加一条用户登录记录
     * @param mixed $args
     * @return array
     */
    public function addStatUserDetail($args = array())
    {
        // TODO
    }
    /**
     * 以天为单位,增涨一条用户登录记数
     * @param mixed $args
     * @return array
     */
    public function increaseStatUser($args = array())
    {
        // TODO
    }
    /**
     * 增加客户端访问记数,以天为单位
     * @param mixed $args
     * @param number $succ
     * @return array
     */
    public function increaseStatClient($args = array())
    {
        // TODO
    }
    /**
     * 增涨浏览器使用记数
     * @return array
     */
    public function increaseStatBrowser()
    {
        $cond = array();
        $arr = array();
        $browser = Utility::browser(true);
        if (!empty($browser) && !empty($browser[0])) {
            $count = count($browser);
            if ($count > 1) {
                $version = array_pop($browser);
                $name = implode(' ', $browser);
            } else {
                $name = implode(' ', $browser);
                $version = '';
            }
            $cond['browser'] = $arr['browser'] = $name;
            $cond['version'] = $arr['version'] = $version;
            //先查找，再更新
            $ret = $this->statBrowser->db()->select(array(), $cond);
            if (!empty($ret)) {
                // +1
                $ret = $this->statBrowser->db()->increase('count', 1, $cond);
            } else {
                // add new
                $arr['count'] = 1;
                $ret = $this->statBrowser->db()->insert(array_keys($arr), $arr);
            }
        }
        return empty($ret) ? false : $ret;
    }

    /**
     * 统计某天或某几天内客户端访问信息
     * @param mixed $args
     * @param number $num 天数
     * @return array
     */
    public function getStatClientCount($args = array(), $num=10)
    {
        // TODO
    }
    /**
     * 获取浏览器使用分布
     * @return array
     */
    public function getStatBrowserDistribution()
    {
        $ret = $this->statBrowser->db()->select(array(), array(), array(), array(), false);
        return empty($ret) ? array() : $ret;
    }

    /**
     * 获取客户端在一定时间内的访问量
     * @param string|int $clientId 客户端ID
     * @param number $period 周期天数
     * @return array
     */
    public function getStatClientByPeriod($clientId, $period = 7)
    {
        $date = date('Y-m-d', time() - 86400);
        $date_offset = date('Y-m-d', time() - 86400 * ($period + 1));
        $fields = "SUM(`count`) as `count`, SUM(`count_visit`) as `count_visit`";
        $condition = array();
        $condition['clientId'] = $clientId;
        // 同一个字段有多个条件时在字段名后加.号和数值
        $condition['date.0'] = array($date_offset, '>=');
        $condition['date.1'] = array($date, '<=');
        $ret = $this->statClient->db()->select($fields, $condition);
        if ($ret) {
            return $ret[0];
        } else {
            return array();
        }
    }
    public function getStatOnlineList($len = 60)
    {
        $order = array('id' => 'DESC');
        $limit = array($len);
        $ret = $this->statOnline->db()->select(array(), array(), $order, $limit, false);
        return empty($ret) ? array() : $ret;
    }

    /**
     * 获取某天的记录
     */
    public function getStatUserDetailDaily($date, $limit = array(1000), $condition = array(), $fields = array())
    {
        $min = $date . ' 00:00:00';
        $max = $date . ' 23:59:59';
        $condition['time.0'] = array($min, '>=');
        $condition['time.1'] = array($max, '<=');
        $order = array('time' => 'ASC');
        $ret = $this->statUserDetail->db()->select($fields, $condition, $order, $limit);
        return empty($ret) ? array() : $ret;
    }
    /**
     * 获取某天记录总数
     */
    public function getStatUserDetailDailyTotal($date, $condition = array())
    {
        $min = $date . ' 00:00:00';
        $max = $date . ' 23:59:59';
        $condition['time.0'] = array($min, '>=');
        $condition['time.1'] = array($max, '<=');
        return $this->statUserDetail->count($condition);
    }

    /**
     * 统计某天记录数
     */
    public function getStatCountClientDaily($date, $clientId = '')
    {
        $condition = array();
        if (!empty($clientId)) {
            $condition['clientId'] = $clientId;
        }
        $total = $this->getStatUserDetailDailyTotal($date, $condition);
        $num = 1000;
        $data = array();
        for ($i = 0; $i < $total; $i = $i + $num) {
            $ret = $this->getStatUserDetailDaily($date, array($i, $num), $condition, array('time'));
            foreach ($ret as $r) {
                $data[] = $r;
            }
        }
        return $data;
    }
    public function getStatUserTop($num, $cond = array())
    {
        if ($num < 1 || empty($cond['startDate']) || empty($cond['endDate'])) {
            return array();
        }
        $startDate = $cond['startDate'];
        $endDate = $cond['endDate'];
        $condition = array();
        $condition['date.0'] = array($startDate, '>=');
        $condition['date.1'] = array($endDate, '<=');
        $condition['$group'] = '`username`';//only string supported
        $fields = "`username`, SUM(`count`) as `count`";
        $ret = $this->statUser->db()->select($fields, $condition, array('count' => 'DESC'), array(0, $num));
        return $ret;
    }
    public function getStatUserTopToday($num, $cond = array())
    {
        if ($num < 1) {
            return array();
        }
        $startDate = date('Y-m-d 00:00:00') ;
        $endDate = date('Y-m-d 23:59:59');
        $condition = array();
        $condition['time.0'] = array($startDate, '>=');
        $condition['time.1'] = array($endDate, '<=');
        $condition['success'] = 1;
        $condition['$group'] = '`username`';//only string supported
        $fields = "`username`, COUNT(`id`) as `count`";
        $ret = $this->statUserDetail->db()->select($fields, $condition, '`count` DESC', array(0, $num));
        return $ret;
    }
    public function getStatClientTop($num = 5, $cond = array())
    {
        if ($num < 1 || empty($cond['startDate']) || empty($cond['endDate'])) {
            return array();
        }
        $startDate = $cond['startDate'];
        $endDate = $cond['endDate'];
        $condition = array();
        $condition['date.0'] = array($startDate, '>=');
        $condition['date.1'] = array($endDate, '<=');
        $condition['$group'] = '`client_id`';//only string supported
        $fields = "`client_id`, SUM(`count`) as `count`";
        $ret = $this->statClient->db()->select($fields, $condition, array('count' => 'DESC'), array(0, $num));
        //$list = $this->getStatUserDetailDaily($date, );
        return $ret;
    }
    public function getStatClientTopToday($num = 5, $condition = array())
    {
        if ($num < 1) {
            return array();
        }
        $startDate = date('Y-m-d 00:00:00') ;
        $endDate = date('Y-m-d 23:59:59');
        $condition = array();
        $condition['time.0'] = array($startDate, '>=');
        $condition['time.1'] = array($endDate, '<=');
        $condition['success'] = 1;
        $condition['$group'] = '`client_id`';//only string supported
        $fields = "`client_id`, COUNT(`id`) as `count`";
        $ret = $this->statUserDetail->db()->select($fields, $condition, '`count` DESC', array(0, $num));
        return $ret;
    }
    
    
    public function getBrowsers($condition = array(), $order = array(), $limit = array())
    {
        return $this->statBrowser->db()->select(array(), $condition, $order, $limit, false);
    }
    public function countBrowsers($condition = array())
    {
        return $this->statBrowser->db()->count($condition);
    }
    
    public function getCurrentOnline()
    {
        return $this->getStatOnlineList(1);
    }
    public function getListOnline($condition = array(), $order = array(), $limit = array())
    {
        return $this->statOnline->db()->select(array(), $condition, $order, $limit, false);
    }
    public function countOnlines($condition = array())
    {
        return $this->statOnline->db()->count($condition);
    }

    public function getStatUserDetailList($condition = array(), $order = array(), $limit = array())
    {
        $ret = $this->statUserDetail->db()->select(array(), $condition, $order, $limit);
        return empty($ret) ? array() : $ret;
    }
}
// PHP END
