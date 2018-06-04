<?php

namespace Lay\Advance\DB;

use Lay\Advance\DB\DataBase;
use Lay\Advance\Core\Ldaplizable;
use Lay\Advance\Core\Identification;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;

class Ldap extends DataBase implements Identification {
    /**
     * ldap服务器访问地址
     *
     * @var string $name
     */
    protected $host;
    /**
     * ldap服务器端口
     *
     * @var string $port
     */
    protected $port;
    /**
     * ldap服务器 admin base dn
     *
     * @var string $string
     */
    protected $name;
    /**
     * ldap服务器 admin password
     *
     * @var string $string
     */
    protected $pass;
    /**
     * ldap服务器 base dn
     *
     * @var string $string
     */
    protected $base;
    /**
     * 数据库连接源
     *
     * @var \Memcache $link
     */
    protected $link;
    /**
     * admin bind flag
     *
     * @var boolean
     */
    protected $bind;
    /**
     * SQL查询产生的结果集
     *
     * @var mixed $result
     */
    protected $result;
    protected $show;
    protected function __construct() {
        global $CFG;
        $this->host = $CFG['ldap_host'];
        $this->port = $CFG['ldap_port'];
        $this->name = $CFG['ldap_name'];
        $this->pass = $CFG['ldap_pass'];
        $this->base = $CFG['ldap_base'];
        $this->show = empty($CFG['ldap_show']) ? false : true;
        $this->alter();
    }
    /**
     * connect ldap database.
     * new instance.
     */
	public function connect() {
        $this->link = ldap_connect($this->host, $this->port);
        if (!ldap_set_option($this->link, LDAP_OPT_PROTOCOL_VERSION, 3)) {
            throw new Exception('Failed to set version to protocol 3.');
        }
        return $this->link;
	}
    public function bind() {
        if(empty($this->bind) && !empty($this->name) && !empty($this->pass)) {
            $this->bind = ldap_bind($this->link, $this->name, $this->pass);
        }
        return empty($this->bind) ? false : true;
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
     * new instance.
     */
    public function alter($name = 'default') {
        global $CFG;
        if(!empty($name) &&!empty($CFG['ldap']) && !empty($CFG['ldap'][$name])) {
            $this->host = $CFG['ldap'][$name]['host'];
            $this->port = $CFG['ldap'][$name]['port'];
            $this->name = $CFG['ldap'][$name]['name'];
            $this->pass = $CFG['ldap'][$name]['pass'];
            $this->base = $CFG['ldap'][$name]['base'];
            $this->show = empty($CFG['ldap'][$name]['show']) ? false : true;
        } else {
            $this->host = $CFG['ldap_host'];
            $this->port = $CFG['ldap_port'];
            $this->name = $CFG['ldap_name'];
            $this->pass = $CFG['ldap_pass'];
            $this->base = $CFG['ldap_base'];
            $this->show = empty($CFG['ldap_show']) ? false : true;
        }
        return $this->connect();
    }
	public function close() {
        if ($this->link) {
            $ret = ldap_close($this->link);
            $this->link = null;
            return $ret;
        }
	}
    public final function get($id, $fields = array()) {
        $ret = $this->resource($id, $fields);
        $arr = $this->toArray(1);
        return empty($arr) ? false : $arr[0];
    }
    public final function add(array $info, $use_last_id = true) {
        $ret = $this->query('insert', array(), $info);
        return empty($ret) ? fasle : $ret;
    }
    public final function del($id) {
        $model = $this->model;
        $pk = $this->model->primary();
        $ret = $this->query('delete', array(), array(), array($pk=>$id));
        return empty($ret) ? fasle : $ret;
    }
    public final function upd($id, array $info) {
        $model = $this->model;
        $pk = $this->model->primary();
        $condition = array();
        $condition[$pk] = $id;
        if($this->model->getPassword()) {
            $condition['has_pass'] = true;
        }
        $ret = $this->query('update', array(), $info, $condition);
        return empty($ret) ? fasle : $ret;
    }
    public final function count(array $info = array()) {
        return false;
    }
    public final function replace(array $info = array()) {
        return false;
    }

    public final function resource($id, $fields = array()) {
        //$link = !empty($this->link) ?: $this->connect();
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = empty($fields) ? array_values($columns) : $this->makeFields($fields);
        $pk = $this->model->primary();
        $condition = "($pk=" . $id . ")";
        $ret = $this->query('search', $vcolumns, array(), $condition);
        return $this->result;
    }
    public final function entry($id, $fields = array()) {
        $ret = $this->resource($id, $scope);
        if(!empty($ret)) {
            $entry = ldap_first_entry($this->link, $this->result);
        }
        return empty($entry) ? false : $entry;
    }

    public final function verify($name, $pass, $scope = array()) {
        $entry = $this->entry($name, $scope);
        $dn = $this->toEntryDn($entry);
        if(!empty($dn) && @ldap_bind($this->link, $dn, $pass)) {
            $arr = $this->toArray(1);
            return empty($arr) ? false : $arr[0];
        } else {
            return false;
        }
    }

    public final function query($cmd, $fields = array(), $values = array(), $condition = array(), $safe = true) {
        $cmd = empty($cmd) ? 'SEARCH' : strtoupper($cmd);
        if(!empty($cmd)) {
            $link = !empty($this->link) ?: $this->connect();
            $model = $this->model;
            $columns = $this->model->columns();
            $pk = $this->model->primary();
            $pkl = array_search($pk, $columns);
            $this->bind();// bind admin
            switch ($cmd) {
                case 'SEARCH':
                    $fields = $this->makeFields($fields);
                    $filter = $this->makeCondition($condition, $safe);
                    $this->result = @ldap_search($this->link, $this->base, $filter, $fields, 0);
                    if(!empty($this->show)) {
                        $fs = implode(',', $fields);
                        Logger::info("$cmd $fs $filter", 'ldap');
                    }
                    break;
                case 'INSERT':
                    if(array_key_exists($pk, $values)) {
                        $id = $values[$pk];
                    } else if(array_key_exists($pkl, $values)) {
                        $id = $values[$pkl];
                    }
                    // only by primary key
                    if(!empty($id)) {
                        $dn = $this->makeDn($id);
                        $arr = $this->makeValues($fields, $values);
                        if(!empty($dn) && !empty($arr)) {
                            $this->bind = empty($this->bind) ? @ldap_bind($this->link, $this->name, $this->pass) : true;
                            $this->result = empty($this->bind) ? false : ldap_add($this->link, $dn, $arr);
                            if(!empty($this->show)) {
                                $val = json_encode($arr);
                                Logger::info("$cmd $dn $val", 'ldap');
                            }
                            if(empty($this->result)) {
                                Logger::error(ldap_error($this->link));
                            }
                        }
                    }
                    break;
                case 'UPDATE':
                    if(array_key_exists($pk, $condition)) {
                        $id = $condition[$pk];
                    } else if(array_key_exists($pkl, $condition)) {
                        $id = $condition[$pkl];
                    }
                    // only by primary key
                    if(!empty($id)) {
                        // unset primary key
                        unset($values[$pk]);
                        unset($values[$pkl]);
                        // 标记是否更改密码
                        $has_pass = empty($condition['has_pass']) ? false : true;
                        $has_class = empty($condition['has_class']) ? false : true;
                        $dn = $this->makeDn($id);
                        $arr = $this->makeValues($fields, $values, true, $has_pass, $has_class);
                        if(!empty($dn) && !empty($arr)) {
                            $this->bind = empty($this->bind) ? @ldap_bind($this->link, $this->name, $this->pass) : true;
                            $this->result = empty($this->bind) ? false : ldap_modify($this->link, $dn, $arr);
                            if(!empty($this->show)) {
                                $val = json_encode($arr);
                                Logger::info("$cmd $dn $val", 'ldap');
                            }
                            if(empty($this->result)) {
                                Logger::error(ldap_error($this->link));
                            }
                        }
                    }
                    break;
                case 'DELETE':
                    if(array_key_exists($pk, $condition)) {
                        $id = $condition[$pk];
                    } else if(array_key_exists($pkl, $condition)) {
                        $id = $condition[$pkl];
                    }
                    // only by primary key
                    if(!empty($id)) {
                        $entry = $this->entry($id);
                        $dn = $this->toEntryDn($entry);
                        if(!empty($entry) && !empty($dn)) {
                            $this->bind = empty($this->bind) ? @ldap_bind($this->link, $this->name, $this->pass) : true;
                            $this->result = empty($this->bind) ? false : ldap_delete($this->link, $dn);
                            if(!empty($this->show)) {
                                $val = json_encode($arr);
                                Logger::info("$cmd $dn", 'ldap');
                            }
                            if(empty($this->result)) {
                                Logger::error(ldap_error($this->link));
                            }
                        }
                    }
                    break;
            }
            return empty($this->result) ? false : $this->result;
        } else {
            return false;
        }
    }
    
    public function makeDn($id) {
        $model = $this->model;
        $pk = $this->model->primary();
        $table = $this->model->table();
        $base = $this->base;
        if(!empty($table)) {
            return "$pk=$id,$table,$base";
        } else {
            return "$pk=$id,$base";
        }

    }
    public function makeFields($fields = array()) {
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        $arr = array();
        if(is_array($fields) && Utility::isAssocArray($fields)) {
            foreach ($fields as $k => $v) {
                if(in_array($k, $vcolumns)) {
                    $arr[] = $k;
                } else if(array_key_exists($k, $columns)) {
                    $arr[] = $columns[$f];
                } else {
                    // ignore
                    continue;
                }
            }
        } else if(is_array($fields)){
            foreach ($fields as $v) {
                if(in_array($v, $vcolumns)) {
                    $arr[] = $v;
                } else if(array_key_exists($v, $columns)) {
                    $arr[] = $columns[$v];
                } else {
                    // ignore
                    continue;
                }
            }
        }
        return empty($arr) ? false : $arr;
    }
    public function makeCondition($condition, $safe) {
        $chip = '';
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        if(is_string($condition)) {
            $chip = "$condition";
        } else if(is_array($condition)) {
            $arr = array();
            foreach ($condition as $f => $c) {
                if(in_array($f, $vcolumns)) {

                } else if(array_key_exists($f, $columns)) {
                    $f = $columns[$f];
                } else {
                    // ignore
                    continue;
                }
                // condition isnot empty string
                if($c !== '') {
                    $chip = $this->bindCondition($chip, $f, $c, $safe);
                }
            }
            if(!empty($chip)) {
                //$chip = "($chip)";
            } else {
                $chip = empty($safe) ? "" : "(objectClass=0)"; 
            }
        }
        return $chip;
    }
    public function bindCondition($chip, $f, $c, $safe) {
        // TODO
        return '';
    }
    public function makeValues($fields = array(), $values = array(), $update = false, $has_pass = false, $has_class = false) {
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        $pk = $this->model->primary();
        if($model instanceof Ldaplizable) {
            $arr = array();
            if(empty($fields)) {
                foreach ($values as $k => $v) {
                    if(in_array($k, $vcolumns)) {
                        $arr[$k] = $v;
                    } else if(array_key_exists($k, $columns)) {
                        $arr[$columns[$k]] = $v;
                    } else {
                        // ignore
                        continue;
                    }
                }
            } else {
                $fs = Utility::isAssocArray($f) ? array_keys($f) : array_values($f);
                foreach ($values as $k => $v) {
                    if(in_array($k, $vcolumns)) {
                        $fr = $k;
                        $fl = array_search($k, $columns);
                        if(in_array($fl, $fs) || in_array($fr, $fs)) {
                            $arr[$fr] = $v;
                        }
                    } else if(array_key_exists($k, $columns)) {
                        $fl = $k;
                        $fr = $columns[$k];
                        if(in_array($fl, $fs) || in_array($fr, $fs)) {
                            $arr[$fr] = $v;
                        }
                    } else {
                        // ignore
                        continue;
                    }
                }
            }
            if(!empty($update) && !empty($has_pass) && $this->model->usePassword()) {
                $arr['userPassword'] = $this->model->getPassword();
            } else if(empty($update) && !empty($arr) && $this->model->usePassword()) {
                $arr['userPassword'] = $this->model->getPassword();
            }
            if(!empty($update) && !empty($has_class)) {
                $arr['objectClass'] = $this->model->objectClass();
            } else if(empty($update) && !empty($arr)) {
                $arr['objectClass'] = $this->model->objectClass();
            }
            return empty($arr) ? false : $arr;
        } else {
            return false;
        }
    }

    public function toEntryDn($entry = null) {
        if(!empty($this->link) && !empty($this->result)) {
            $entry = empty($entry) ? ldap_first_entry($this->link, $this->result) : $entry;
            if(!empty($entry)) {
                return ldap_get_dn($this->link, $entry);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function toArray($count = 0, $columns = array()) {
        $model = $this->model;
        $columns = empty($columns) ? $this->model->columns() : $columns;
        $vcolumns = array_values($columns);
        $rows = array();
        if (!empty($this->link) && !empty($this->result) && $count == 1) {
            $entry = ldap_first_entry($this->link, $this->result);
            if(!empty($entry)) {
                $arr = array();
                foreach ($columns as $p => $f) {
                    $val = ldap_get_values($this->link, $entry, $f);
                    if(!empty($val) && $val['count'] > 0) {
                        $arr[$p] = $val[0];
                    }
                }
                $rows[] = $arr;
            }
        } else if (!empty($this->link) && !empty($this->result) && $count != 0) {
            $i = 0;
            $entry = ldap_first_entry($this->link, $this->result);
            if(!empty($entry)) {
                do {
                    if ($i < $count) {
                        $arr = array();
                        foreach ($columns as $p => $f) {
                            $val = ldap_get_values($this->link, $entry, $f);
                            if(!empty($val) && $val['count'] > 0) {
                                $arr[$p] = $val[0];
                            }
                        }
                        $rows[] = $arr;
                        $i ++;
                    } else {
                        break;
                    }
                } while ($entry = ldap_next_entry($this->link, $entry));
            }
        } else if(!empty($this->link) && !empty($this->result)) {
            $i = 0;
            $entry = ldap_first_entry($this->link, $this->result);
            if(!empty($entry)) {
                do {
                    $arr = array();
                    foreach ($columns as $p => $f) {
                        $val = ldap_get_values($this->link, $entry, $f);
                        if(!empty($val) && $val['count'] > 0) {
                            $arr[$p] = $val[0];
                        }
                    }
                    $rows[] = $arr;
                } while ($entry = ldap_next_entry($this->link, $entry));
            }
        }
        return $rows;
    }
}
// PHP END