<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Paging;

use Dcux\SSO\Model\StatUserDetail;
use Dcux\SSO\OAuth2\OAuth2;

class StatUserDetailService extends service
{
    private $statUserDetail;
    public static function getInstance()
    {
        $instance = parent::getInstance();
        return $instance;
    }
    public function model()
    {
        $this->statUserDetail = StatUserDetail::getInstance();
        return $this->statUserDetail;
    }
    public static function read($args = '')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            $condition=$args;
            if ($condition['id']) {
                return $instance->get($condition['id']);
            }
        } else {
            $condition=array();
            return $instance->getAll();
        }
        return $instance->model()->query(array(), $condition);
    }
    public static function readSUDetailPaging()
    {
        $p = new Paging();
        return $p->build($_REQUEST);
    }
    public static function readSUDetailTotal($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readSUDetailPaging();
        $ret = self::getInstance()->count();
        return empty($ret) ? 0 : $ret;
    }
    public static function readSUDetailList($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readSUDetailPaging();
        return self::getInstance()->getSUDetailListPaging(array(), array('id' => 'ASC'), $paging->toLimit());
    }
    public function getSUDetailListPaging($condition = array(), $order = array(), $limit = array())
    {
        $ret = $this->model()->db()->select(array(), $condition, $order, $limit, false);
        return empty($ret) ? array() : $ret;
    }
    public static function counts($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            $condition=$args;
        } else {
            return false;
        }
        return $instance->count($condition);
    }
    public static function deleteByDay($day=30)
    {
    }
    public static function write($appLog)
    {
        $instance = self::getInstance();
        $arr = &$appLog;
        $arr['ip'] = sprintf("%u", ip2long(Utility::ip()));
        $arr['os'] = Utility::os();
        $arr['browser'] = Utility::browser();
        $arr['time'] = date('Y-m-d H:i:s');
        $arr['ua'] = Utility::ua();
        $arr['referer'] = OAuth2::getReferer(true);
        $result = $instance->add($arr);
        return $result;
    }
}
