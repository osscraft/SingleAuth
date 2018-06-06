<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Paging;

use Dcux\SSO\Model\Setting;

class SettingService extends Service
{
    private $setting;
    public static function getInstance()
    {
        $instance = parent::getInstance();
        return $instance;
    }
    public function model()
    {
        $this->setting = Setting::getInstance();
        return $this->setting;
    }
    //读取一条或多条记录
    public static function read($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            if ($args['k']) {
                return $instance->get($args['k']);
            }
        } else {
            return $instance->getAll();
        }
        return $instance->model()->query(array(), $args);
    }
    //删除一条记录
    public static function delete($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            if ($args['k']) {
                return $instance->del($args['k']);
            } else {
                return false;
                //return $instance->model()->db()->delete($args);
            }
        } else {
            return false;
        }
    }
    //插入一条记录
    public static function insert($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            if ($args['k']&&$args['v']) {
                return	$instance->add($args);
            }
        }
    }
    //修改一条记录
    public static function update($args)
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            if ($args['k']) {
                return $instance->upd($args['k'], $args);
            }
        }
        return false;
    }
    public static function readSettingPaging()
    {
        $p = new Paging();
        return $p->build($_REQUEST);
    }
    public static function readSettingTotal($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readSettingPaging();
        $ret = self::getInstance()->count();
        return empty($ret) ? 0 : $ret;
    }
    public static function readSettingList($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readSettingPaging();
        return self::getInstance()->getSettingListPaging(array(), array('id' => 'ASC'), $paging->toLimit());
    }
    public function getSettingListPaging($condition = array(), $order = array(), $limit = array())
    {
        $ret = $this->model()->db()->select(array(), $condition, $order, $limit, false);
        return empty($ret) ? array() : $ret;
    }
    public function getSettingListAll($condition = array(), $order = array())
    {
        $ret = $this->model()->db()->select(array(), $condition, $order, array(), false);
        return empty($ret) ? array() : $ret;
    }
}
