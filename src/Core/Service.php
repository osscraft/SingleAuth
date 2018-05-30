<?php

namespace Dcux\Core;

use Dcux\Core\Singleton;
use Dcux\Core\Serviceable;
use Dcux\DB\Uniqueness;

abstract class Service extends Singleton implements Serviceable, Uniqueness {
    public function get($id, $fields = array()) {
        return $this->model()->get($id, $fields);
    }
    public function add(array $info) {
        return $this->model()->add($info);
    }
    public function del($id) {
        return $this->model()->del($id);
    }
    public function upd($id, array $info) {
        return $this->model()->upd($id, $info);
    }
    public function count(array $info = array()) {
        return $this->model()->count($info);
    }
    public function replace(array $info = array()) {
        return $this->model()->replace($info);
    }

    /**
     * unsafe
     * @param array $condition
     * @param array $order
     * @param array $limit
     */
    public function getAll($order = array(), $limit = array()) {
        return $this->model()->db()->select(array(), array(), $order, $limit, false);
    }
    /**
     * unsafe, get list by sql
     * @param string $sql
     */
    public function querys($sql){
        return $this->query($sql);
    }
    /**
     * unsafe, get list by sql
     * @param string $sql
     */
    public function query($sql) {
        $ret = $this->model()->db()->query($sql);
        return $this->model()->db()->toArray();
    }
    /**
     * query only by ids
     * @param array $ids
     */
    public function getList(array $ids = array()) {
        return $this->model()->lists($ids);
    }
    /**
     * safe
     * @param array $condition
     * @param array $order
     * @param array $limit
     */
    public function getSearchList($condition = array(), $order = array(), $limit = array()) {
        // TODO search by engine
        if($this->model()->engine()) {
            return $this->model()->engine()->search(array(), $condition, $order, $limit);
        } else {
            return false;
        }
    }
    /**
     * safe
     * @param array $condition
     * @param array $order
     * @param array $limit
     */
    public function getQueryList($condition = array(), $order = array(), $limit = array()) {
        return $this->model()->db()->select(array(), $condition, $order, $limit);
    }
    /**
     * unsafe
     * @param array $condition
     * @param array $order
     * @param array $limit
     */
    public function getConditionList($condition = array(), $order = array(), $limit = array()) {
        return $this->model()->db()->select(array(), $condition, $order, $limit, false);
    }

    /**
     * set by unique fields' value
     */
    public function updByUnique($unique, array $info) {
        // not support
        return false;
    }
    /**
     * get by unique fields' value
     */
    public function getByUnique($unique) {
        $model = $this->model();
        if($model instanceof Uniqueness) {
            return $this->model()->getByUnique($unique);
        } else {
            return false;
        }
    }
    /**
     * delete by unique fields' value
     */
    public function delByUnique($unique) {
        // not support
        return false;
    }

    public function freeResult() {
        $db = $this->model()->db();
        if($db instanceof Mysql) {
            return $this->model()->db()->freeResult();
        } else {
            return true;
        }
    }
}
// PHP END