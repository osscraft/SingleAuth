<?php

namespace Dcux\DB;

use Dcux\DB\DataBase;

class Engine extends DataBase {
    protected function __construct() {
        global $CFG;
        /*$this->server = $CFG['mysql_host'];
        $this->username = $CFG['mysql_name'];
        $this->password = $CFG['mysql_password'];
        $this->dbname = $CFG['mysql_database'];
        $this->encode = empty($CFG['mysql_encode']) ? false : $CFG['mysql_encode'];
        $this->showsql = empty($CFG['mysql_showsql']) ? false : true;*/
    }
    /**
     * connect mysql database
     */
	public function connect() {
        return $this->link;
	}
    /**
     * 选择数据库名称
     *
     * @return mixed
     */
    public function choose($dbname) {
    	return true;
    }
    /**
     * another connect
     */
    public function alter($name = '') {
        global $CFG;
        if(!empty($name) &&!empty($CFG['memcache']) && !empty($CFG['memcache'][$name])) {
            /*$this->server = $CFG['mysql'][$name]['host'];
            $this->username = $CFG['mysql'][$name]['name'];
            $this->password = $CFG['mysql'][$name]['password'];
            $this->dbname = $CFG['mysql'][$name]['database'];
            $this->encode = empty($CFG['mysql'][$name]['encode']) ? false : $CFG['mysql'][$name]['encode'];
            $this->showsql = empty($CFG['mysql'][$name]['showsql']) ? false : true;*/
        } else {
            /*$this->server = $CFG['mysql_host'];
            $this->username = $CFG['mysql_name'];
            $this->password = $CFG['mysql_password'];
            $this->dbname = $CFG['mysql_database'];
            $this->encode = empty($CFG['mysql_encode']) ? false : $CFG['mysql_encode'];
            $this->showsql = empty($CFG['mysql_showsql']) ? false : true;*/
        }
        return $this->connect();
    }
	public function close() {
        /*if ($this->link)
            return mysqli_close($this->link);*/
	}
    public final function get($id, $fields = array()) {
        $model = $this->model;
        $columns = $this->model->columns();
        $pk = $this->model->primary();
        $fields = array_values($columns);
        $condition = array($pk => $id);
        $order = array();
        $limit = array(1);
        $sql = $this->makeSelect($fields, $condition, $order, $limit);
        $ret = $this->query($sql);
        $arr = $this->toArray(1);
        return empty($arr) ? false : $arr[0];
    }
    public final function add(array $info) {
        return false;
    }
    public final function del($id) {
        return false;
    }
    public final function upd($id, array $info) {
        return false;
    }
    public final function count(array $info = array()) {
        $sql = $this->makeCount($info);
        $ret = $this->query($sql);
        $arr = $this->toArray(1);
        return empty($arr) ? false : $arr[0]['count'];
    }

    
}