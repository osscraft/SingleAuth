<?php

namespace Dcux\Core;

use Dcux\Core\App;
use Dcux\Core\Model;
use Dcux\Core\Identification;
use Dcux\Core\Ldaplizable;
use Dcux\DB\DataBase;
use Dcux\DB\Uniqueness;
use Dcux\Util\Logger;

abstract class ModelUnique extends Model implements Uniqueness
{
    const E_GET_UNIQUE = 'model:event:get.unique';
    protected function listen()
    {
        parent::listen();
        App::$_event->listen(get_class($this), self::E_GET_UNIQUE, array($this, 'onGetByUnique'));
    }
    /**
     * 返回模型属性名对应数据表中的唯一键字符串或数组
     * @return mixed array|string
     */
    abstract public function unique();

    /**
     * getByUnique后触发
     */
    public function onGetByUnique($ret, $unique)
    {
        $cacher = $this->cacher();
        if (!empty($cacher) && !empty($ret)) {
            $cacher->updByUnique($unique, $ret);
        }
    }

    /**
     * del后触发
     */
    public function onDel($ret, $id, $info = array())
    {
        $cacher = $this->cacher();
        $columns = $this->columns();
        $uk = $this->unique();
        if (!empty($uk) && !empty($info) && !empty($cacher) && !empty($ret)) {
            $unique = $this->makeUnique($info);
            if (!empty($unique)) {
                $cacher->delByUnique($unique);
            }
        }
        parent::onDel($ret, $id, $info);
    }
    /**
     * upd后触发
     */
    public function onUpd($ret, $id, $info)
    {
        $cacher = $this->cacher();
        $uk = $this->unique();
        if (!empty($uk) && !empty($cacher) && !empty($info) && !empty($ret)) {
            $unique = $this->makeUnique($info);
            if (!empty($unique)) {
                $cacher->delByUnique($unique);
            }
        }
        parent::onUpd($ret, $id, $info);
    }
    /**
     * replace后触发
     */
    public function onReplace($ret, $info)
    {
        $cacher = $this->cacher();
        $uk = $this->unique();
        if (!empty($uk) && !empty($cacher) && !empty($info) && !empty($ret)) {
            $unique = $this->makeUnique($info);
            if (!empty($unique)) {
                $cacher->delByUnique($unique);
            }
        }
        parent::onReplace($ret, $info);
    }
    /**
     * make unqiue fields' condition array or string
     */
    protected function makeUnique($info)
    {
        $cacher = $this->cacher();
        $columns = $this->columns();
        $uk = $this->unique();
        if (is_array($uk)) {
            $unique = array();
            foreach ($uk as $k) {
                $p = array_search($k, $columns);
                if (!empty($info[$k])) {
                    $unique[$k] = $info[$k];
                } elseif (!empty($info[$p])) {
                    $unique[$k] = $info[$p];
                }
            }
        } elseif (is_string($uk)) {
            $p = array_search($uk, $columns);
            if (!empty($info[$k])) {
                $unique = $info[$k];
            } elseif (!empty($info[$p])) {
                $unique = $info[$p];
            }
        } else {
            return false;
        }
        return $unique;
    }

    /**
     * set by unique fields' value
     */
    public function updByUnique($unique, array $info)
    {
        // not support
        return false;
    }
    /**
     * get by unique fields' value
     */
    public function getByUnique($unique)
    {
        $cacher = $this->cacher();
        $db = $this->db();
        if (!empty($cacher) && $cacher instanceof Uniqueness) {
            $ret = $cacher->getByUnique($unique);
        }
        if (empty($ret) && $db instanceof Uniqueness) {
            $ret = $this->db()->getByUnique($unique);
            App::$_event->fire(get_class($this), self::E_GET_UNIQUE, array($ret, $unique));
            return $ret;
        } else {
            return empty($ret) ? false : $ret;
        }
    }
    /**
     * delete by unique fields' value
     */
    public function delByUnique($unique)
    {
        // not support
        return false;
    }
}
