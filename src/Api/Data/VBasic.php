<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;

use Dcux\Api\Data\VList;
use stdClass;

abstract class VBasic extends Component
{
    /**
     * 构造方法
     * @return VBasic
     */
    public function __construct()
    {
        foreach ($this->properties() as $pro => $def) {
            $this->$pro = $def;
        }
    }
    /**
     * 返回对象属性映射关系
     * @return array
     */
    public function mapping()
    {
        return array();
    }
    /**
     * 返回对象所有属性值规则
     * @return array
     */
    public function rules()
    {
        return array();
    }
    /**
     * 返回规则转换后的值
     * @return array
     */
    public function format($val, $key, $option = array())
    {
        return $val;
    }

    /**
     * @param object|array $obj
     */
    public static function parse($obj)
    {
        $class = get_called_class();
        $object = new $class;
        $mapping = $object->mapping();
        if (is_object($obj)) {
            foreach ($object->properties() as $pro => $def) {
                // property and property mapping
                $map = array_key_exists($pro, $mapping) ? $mapping[$pro] : false;
                $object->__set($pro, !isset($obj->$pro) ? ($map && isset($obj->$map) ? $obj->$map : $def) : $obj->$pro);
            }
        } elseif (is_array($obj) && !empty($obj)) {
            foreach ($object->properties() as $pro => $def) {
                // property and property mapping
                $map = array_key_exists($pro, $mapping) ? $mapping[$pro] : false;
                $object->__set($pro, !isset($obj[$pro]) ? ($map && isset($obj[$map]) ? $obj[$map] : $def) : $obj[$pro]);
            }
        }
        return $object;
    }
    public static function parseSimple($arr)
    {
        $class = get_called_class();
        return $class::parse($obj);
    }
    /**
     * @param array $arr
     * @param boolean $simple
     */
    public static function parseArray($arr, $simple = false)
    {
        $class = get_called_class();
        $ret = array();
        foreach ($arr as $val) {
            $ret[] = empty($simple) ? $class::parse($val) : $class::parseSimple($val);
        }
        return $ret;
    }
    /**
     * @param array $arr
     */
    public static function parseArraySimple($arr)
    {
        $class = get_called_class();
        return $class::parseArray($arr, true);
    }
    /**
     * @param array|object $list
     * @param int $total
     * @param string $since
     * @param boolean $simple
     */
    public static function parseList($list, $total = 0, $hasNext = false, $since = '', $simple = false)
    {
        $class = get_called_class();
        $vlist = new VList();
        if (is_object($list)) {
            $arr = empty($list->list) ? array() : $list->list;
            $total = empty($list->total) ? count($arr) : $list->total;
            $hasNext = empty($list->hasNext) ? false : true;
            $since = empty($list->since) ? '' : $list->since;
        } elseif (is_array($list) && Utility::isAssocArray($list)) {
            $arr = empty($list['list']) ? array() : $list['list'];
            $total = empty($list['total']) ? count($arr) : $list['total'];
            $hasNext = empty($list['hasNext']) ? false : true;
            $since = empty($list['since']) ? '' : $list['since'];
        } elseif (is_array($list)) {
            $arr = $list;
        } else {
            $arr = array();
        }
        $vlist->__set('list', $class::parseArray($arr, $simple));
        $vlist->__set('total', empty($total) ? count($arr) : $total);
        $vlist->__set('hasNext', empty($hasNext) ? false : true);
        $vlist->__set('since', empty($since) ? '' : $since);
        return $vlist;
    }
    /**
     * @param array|object $list
     * @param int $total
     * @param string $since
     */
    public static function parseListSimple($list, $total = 0, $hasNext = false, $since = '')
    {
        $class = get_called_class();
        return $class::parseList($list, $total, $hasNext, $since, true);
    }
}
// PHP END
