<?php

namespace Dcux\DB;

use Dcux\DB\DataBase;
use Dcux\DB\Querying;
use Dcux\DB\Uniqueness;
use Dcux\Util\Utility;
use Dcux\Util\Logger;

class Mysql extends DataBase implements Querying, Uniqueness
{
    /**
     * MySQL服务器访问地址
     *
     * @var string $name
     */
    protected $server;
    /**
     * 登录数据库用户名
     *
     * @var string $username
     */
    protected $username;
    /**
     * 登录数据库用户密码
     *
     * @var string $password
     */
    protected $password;
    /**
     * 选择的数据库名
     *
     * @var string $dbname
     */
    protected $dbname;
    /**
     * 数据库连接源
     *
     * @var mixed $link
     */
    protected $link;
    /**
     * SQL查询产生的结果集
     *
     * @var mixed $result
     */
    protected $result;
    /**
     * 数据库编码
     * @var string
     */
    protected $encode;
    /**
     * 记录SQL日志
     * @var boolean
     */
    protected $showsql;
    public function __construct()
    {
        global $CFG;
        $this->server = $CFG['mysql_host'] . ":" . $CFG['mysql_port'];
        $this->username = $CFG['mysql_name'];
        $this->password = $CFG['mysql_password'];
        $this->dbname = $CFG['mysql_database'];
        $this->encode = empty($CFG['mysql_encode']) ? false : $CFG['mysql_encode'];
        $this->showsql = empty($CFG['mysql_showsql']) ? false : true;
        $this->alter();
    }
    /**
     * connect mysql database
     */
    public function connect()
    {
        $this->link = mysqli_connect($this->server, $this->username, $this->password) or die("Could not connect: " . mysqli_error($this->link));
        if ($this->dbname) {
            $this->choose($this->dbname) or die("Could not select: " . mysqli_error($this->link));
        }
        return $this->link;
    }
    /**
     * 选择数据库名称
     *
     * @return mixed
     */
    public function choose($dbname)
    {
        if ($this->link) {
            return mysqli_select_db($this->link, $dbname);
        }
    }
    /**
     * another connect
     */
    public function alter($name = 'default')
    {
        global $CFG;
        if (!empty($name) &&!empty($CFG['mysql']) && !empty($CFG['mysql'][$name])) {
            $this->server = $CFG['mysql'][$name]['host'];
            $this->username = $CFG['mysql'][$name]['name'];
            $this->password = $CFG['mysql'][$name]['password'];
            $this->dbname = $CFG['mysql'][$name]['database'];
            $this->encode = empty($CFG['mysql'][$name]['encode']) ? false : $CFG['mysql'][$name]['encode'];
            $this->showsql = empty($CFG['mysql'][$name]['showsql']) ? false : true;
        } else {
            $this->server = $CFG['mysql_host'];
            $this->username = $CFG['mysql_name'];
            $this->password = $CFG['mysql_password'];
            $this->dbname = $CFG['mysql_database'];
            $this->encode = empty($CFG['mysql_encode']) ? false : $CFG['mysql_encode'];
            $this->showsql = empty($CFG['mysql_showsql']) ? false : true;
        }
        return empty($this->link) ? true : $this->connect();
    }
    public function close()
    {
        if ($this->link) {
            $ret = mysqli_close($this->link);
            $this->link = null;
            return $ret;
        }
    }
    final public function get($id, $fields = array())
    {
        $model = $this->model;
        $columns = $this->model->columns();
        $pk = $this->model->primary();
        $fields = empty($fields) ? array_values($columns) : $fields;
        $condition = array($pk => $id);
        $order = array();
        $limit = array(1);
        $sql = $this->makeSelect($fields, $condition, $order, $limit);
        $ret = $this->query($sql);
        $arr = $this->toArray(1);
        return empty($arr) ? false : $arr[0];
    }
    final public function add(array $info)
    {
        $sql = $this->makeInsert(array_keys($info), $info);
        $ret = $this->query($sql);
        $lastid = $this->toLastId();
        return empty($lastid) ? false : $lastid;
    }
    final public function del($id)
    {
        $model = $this->model;
        $pk = $this->model->primary();
        $condition = array($pk => $id);
        $sql = $this->makeDelete($condition);
        $ret = $this->query($sql);
        $ret = $this->toResult();
        return empty($ret) ? false : $ret;
    }
    final public function upd($id, array $info)
    {
        $model = $this->model;
        $pk = $this->model->primary();
        $condition = array($pk => $id);
        $sql = $this->makeUpdate(array(), $info, $condition);
        $ret = $this->query($sql);
        $ret = $this->toResult();
        return empty($ret) ? false : $ret;
    }
    final public function count(array $info = array())
    {
        $sql = $this->makeCount($info);
        $ret = $this->query($sql);
        $arr = $this->toArray(1);
        return empty($arr) ? false : $arr[0]['num'];
    }
    final public function replace(array $info = array())
    {
        $model = $this->model;
        $pk = $this->model->primary();
        $columns = $this->model->columns();
        $pkl = array_search($pk, $columns);
        $sql = $this->makeInsert(array_keys($info), $info, true);
        $ret = $this->query($sql);
        if (!empty($ret)) {
            if (array_key_exists($pk, $info)) {
                $lastid = $info[$pk];
            } elseif (array_key_exists($pkl, $info)) {
                $lastid = $info[$pkl];
            } else {
                $lastid = $this->toLastId();
            }
        }
        return empty($lastid) ? false : $lastid;
    }
    final public function query($sql, $encoding = 'UTF8', array $option = array())
    {
        //connect
        if (empty($this->link)) {
            $this->connect();
        }
        if (!empty($this->link)) {
            if ($this->encode) {
                $this->link->query("SET NAMES " . $this->encode);
            } elseif ($encoding) {
                $this->link->query("SET NAMES $encoding");
            }
            if ($sql) {
                $this->result = $this->link->query($sql);
            }
            if ($sql && $this->showsql) {
                Logger::info($sql, 'sql');
            }
        } else {
            $this->result = false;
        }
        return $this->result;
    }
    final public function select($fields = array(), $condition = array(), $order = array(), $limit = array(), $safe = true)
    {
        $columns = $this->model->columns();
        $fields = empty($fields) ? array_values($columns) : $fields;
        $sql = $this->makeSelect($fields, $condition, $order, $limit, $safe);
        $ret = $this->query($sql);
        $arr = $this->toArray();
        return $arr;
    }
    final public function insert($fields = array(), $values = array(), $replace = false)
    {
        $sql = $this->makeInsert($fields, $values, $replace);
        $ret = $this->query($sql);
        if (empty($replace)) {
            $lastid = $this->toLastId();
            return empty($lastid) ? false : $lastid;
        } else {
            return empty($ret) ? false : $ret;
        }
    }
    final public function update($fields = array(), $values = array(), $condition = array(), $safe = true)
    {
        $sql = $this->makeUpdate($fields, $values, $condition, $safe);
        $ret = $this->query($sql);
        return empty($ret) ? false : $ret;
    }
    final public function delete($condition = array(), $safe = true)
    {
        $sql = $this->makeDelete($condition, $safe);
        $ret = $this->query($sql);
        return empty($ret) ? false : $ret;
    }
    final public function increase($field, $num = 1, $condition = array(), $safe = true)
    {
        $sql = $this->makeIncrease($field, $num, $condition, $safe);
        $ret = $this->query($sql);
        return empty($ret) ? false : $ret;
    }

    /**
     * @param array|string $unique
     * @param array $info
     */
    final public function updByUnique($unique, array $info)
    {
        $condition = $this->makeUnique($unique);
        if (!empty($condition)) {
            return $this->update(array(), $info, $condition);
        } else {
            return false;
        }
    }
    /**
     * @param array|string $unique
     */
    final public function getByUnique($unique)
    {
        $condition = $this->makeUnique($unique);
        if (!empty($condition)) {
            $columns = $this->model->columns();
            $fields = array_values($columns);
            $sql = $this->makeSelect($fields, $condition, $order, $limit);
            $ret = $this->query($sql);
            $arr = $this->toArray(1);
            return empty($arr) ? false : $arr[0];
        } else {
            return false;
        }
    }
    /**
     * @param array|string $unique
     */
    final public function delByUnique($unique)
    {
        $condition = $this->makeUnique($unique);
        if (!empty($condition)) {
            return $this->delete($condition);
        } else {
            return false;
        }
    }
    /**
     * make unique fields' condition array
     */
    public function makeUnique($unique)
    {
        $model = $this->model;
        $columns = $this->model->columns();
        $uk = $this->model->unique();
        $condition = array();
        if (!empty($uk) && is_array($uk) && is_array($unique)) {
            foreach ($uk as $k) {
                $p = array_search($k, $columns);
                if (!empty($unique[$k])) {
                    $condition[$k] = $unique[$k];
                } elseif (!empty($unique[$p])) {
                    $condition[$k] = $unique[$p];
                } else {
                    return false;
                }
            }
        } elseif (!empty($uk) && is_string($uk)) {
            $condition[$uk] = $unique;
        } else {
            return false;
        }
        return $condition;
    }

    /**
     * 提取结果集
     *
     * @return mixed
     */
    public function toResult()
    {
        return $this->result;
    }
    /**
     * 提取最后插入ID
     *
     * @return mixed
     */
    public function toLastId()
    {
        return empty($this->result) ? false : (mysqli_insert_id($this->link) || $this->result);
    }
    /**
     * 提取数据源，将其转换成数组
     *
     * @param int $count
     *            读取条数
     * @param mixed $result
     *            数据源
     * @return array
     */
    public function toArray($count = 0)
    {
        $result = $this->result;
        $rows = array();
        if ($result && $count == 1) {
            $row = mysqli_fetch_array($result, MYSQL_ASSOC);
            if (!empty($row)) {
                $rows[] = $row;
            }
        } elseif ($result && $count != 0) {
            $i = 0;
            if (@mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                    if ($i < $count) {
                        $rows[$i] = (array) $row;
                        $i ++;
                    } else {
                        break;
                    }
                }
            }
        } elseif ($result) {
            $i = 0;
            if (@mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                    $rows[$i] = (array) $row;
                    $i ++;
                }
            }
        }
        return $rows;
    }
    /**
     * make select SQL
     */
    final public function makeSelect($fields = array(), $condition = array(), $order = array(), $limit = array(), $safe = true)
    {
        $link = !empty($this->link) ?: $this->connect();
        $model = $this->model;
        $schema = $this->model->schema();
        $table = $this->model->table();
        $t = empty($schema) ? "`$table`" : "`$schema`.`$table`";
        $f = empty($fields) ? '*' : $this->makeFields($fields, true);
        $w = empty($condition) ? (empty($safe) ? '' : 'WHERE 1 = 0') : $this->makeCondition($condition, $safe);
        $g = !is_array($condition) || empty($condition['$group']) ? '' : $this->makeGroup($condition['$group'], $safe);
        $h = !is_array($condition) || empty($condition['$having']) ? '' : $this->makeHaving($condition['$having'], $safe);
        $o = empty($order) ? '' : $this->makeOrder($order);
        $l = empty($limit) ? '' : $this->makeLimit($limit, $safe);
        $sql = "SELECT $f FROM $t $w $g $h $o $l";
        return $sql;
    }
    /**
     * make select count SQL
     */
    final public function makeCount($condition = array(), $safe = false)
    {
        $link = !empty($this->link) ?: $this->connect();
        $model = $this->model;
        $schema = $this->model->schema();
        $table = $this->model->table();
        $t = empty($schema) ? "`$table`" : "`$schema`.`$table`";
        $w = empty($condition) ? (empty($safe) ? '' : 'WHERE 1 = 0') : $this->makeCondition($condition, $safe);
        $sql = "SELECT COUNT(*) AS num FROM $t $w LIMIT 1";
        return $sql;
    }
    /**
     * make insert SQL
     */
    final public function makeInsert($fields = array(), $values = array(), $replace = false)
    {
        $link = !empty($this->link) ?: $this->connect();
        $model = $this->model;
        $schema = $this->model->schema();
        $table = $this->model->table();
        $t = empty($schema) ? "`$table`" : "`$schema`.`$table`";
        $f = empty($fields) ? '' : $this->makeFields($fields);
        $v = empty($values) ? '' : $this->makeValues($fields, $values);
        if (empty($replace)) {
            $sql = "INSERT INTO $t ( $f ) VALUES ( $v )";
        } else {
            $sql = "REPLACE INTO $t ( $f ) VALUES ( $v )";
        }
        return $sql;
    }
    /**
     * make update SQL
     */
    final public function makeUpdate($fields = array(), $values = array(), $condition = array(), $safe = true)
    {
        $link = !empty($this->link) ?: $this->connect();
        $model = $this->model;
        $schema = $this->model->schema();
        $table = $this->model->table();
        $t = empty($schema) ? "`$table`" : "`$schema`.`$table`";
        $s = empty($values) ? '' : $this->makeSetter($fields, $values);
        $w = empty($condition) ? (empty($safe) ? '' : 'WHERE 1 = 0') : $this->makeCondition($condition, $safe);
        $sql = "UPDATE $t SET $s $w";
        return $sql;
    }
    /**
     * make delete SQL
     */
    final public function makeDelete($condition = array(), $safe = true)
    {
        $link = !empty($this->link) ?: $this->connect();
        $model = $this->model;
        $schema = $this->model->schema();
        $table = $this->model->table();
        $t = empty($schema) ? "`$table`" : "`$schema`.`$table`";
        $w = empty($condition) ? (empty($safe) ? '' : 'WHERE 1 = 0') : $this->makeCondition($condition, $safe);
        $sql = "DELETE FROM $t $w";
        return $sql;
    }
    final public function makeIncrease($field, $num = 1, $condition = array(), $safe = true)
    {
        $link = !empty($this->link) ?: $this->connect();
        $model = $this->model;
        $schema = $this->model->schema();
        $table = $this->model->table();
        $t = empty($schema) ? "`$table`" : "`$schema`.`$table`";
        $s = empty($field) ? '' : $this->makeIncreaseSetter($field, $num);
        $w = empty($condition) ? (empty($safe) ? '' : 'WHERE 1 = 0') : $this->makeCondition($condition, $safe);
        $sql = "UPDATE $t SET $s $w";
        return $sql;
    }
    /**
     * make insert or select fields SQL chip
     */
    public function makeFields($f, $as = false)
    {
        $chip = '';
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        $arr = array();
        if (is_string($f)) {
            $chip = $f;
        } elseif (is_array($f) && Utility::isAssocArray($f)) {
            foreach ($f as $k => $v) {
                if (in_array($k, $vcolumns)) {
                    $asf = array_search($k, $columns);
                    $arr[] = empty($as) ? "`$k`" : "`$k` AS `$asf`";
                } elseif (array_key_exists($k, $columns)) {
                    $asf = $k;
                    $arr[] = empty($as) ? "`{$columns[$f]}`" : "`{$columns[$f]}` AS `$asf`";
                } else {
                    // ignore
                    continue;
                }
            }
        } elseif (is_array($f)) {
            foreach ($f as $v) {
                if (in_array($v, $vcolumns)) {
                    $asf = array_search($v, $columns);
                    $arr[] = empty($as) ? "`$v`" : "`$v` AS `$asf`";
                } elseif (array_key_exists($v, $columns)) {
                    $asf = $v;
                    $arr[] = empty($as) ? "`{$columns[$v]}`" : "`{$columns[$v]}` AS `$asf`";
                } else {
                    // ignore
                    continue;
                }
            }
        }
        if (!empty($arr)) {
            $chip = implode(", ", $arr);
        }
        return $chip;
    }
    /**
     * make insert values SQL chip
     */
    public function makeValues($f, $v)
    {
        $chip = '';
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        $arr = array();
        if (empty($f)) {
            foreach ($v as $k => $val) {
                if (in_array($k, $vcolumns)) {
                    $arr[] = mysqli_real_escape_string($this->link, $val);
                } elseif (array_key_exists($k, $columns)) {
                    $arr[] = mysqli_real_escape_string($this->link, $val);
                } else {
                    // ignore
                    continue;
                }
            }
        } else {
            $fs = Utility::isAssocArray($f) ? array_keys($f) : array_values($f);
            foreach ($v as $k => $val) {
                if (in_array($k, $vcolumns)) {
                    $fr = $k;
                    $fl = array_search($k, $columns);
                    // when that is the valid field
                    if (in_array($fl, $fs) || in_array($fr, $fs)) {
                        $arr[] = mysqli_real_escape_string($this->link, $val);
                    }
                } elseif (array_key_exists($k, $columns)) {
                    $fl = $k;
                    $fr = $columns[$k];
                    // when that is the valid field
                    if (in_array($fl, $fs) || in_array($fr, $fs)) {
                        $arr[] = mysqli_real_escape_string($this->link, $val);
                    }
                } else {
                    // ignore
                    continue;
                }
            }
        }
        if (!empty($arr)) {
            $chip = "'" . implode("', '", $arr) . "'";
        }
        return $chip;
    }
    /**
     * make select GROUP BY SQL chip
     * only string supported
     */
    public function makeGroup($group, $safe)
    {
        if (empty($group) || !is_string($group)) {
            return '';
        } else {
            return "GROUP BY $group";
        }
    }
    /**
     * make select HAVING SQL chip,
     * only string supported
     */
    public function makeHaving($having, $safe)
    {
        if (empty($having) || !is_string($having)) {
            return '';
        } else {
            return "HAVING $having";
        }
    }
    /**
     * make select where condition SQL chip
     */
    public function makeCondition($condition, $safe)
    {
        $chip = '';
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        if (is_string($condition)) {
            $chip = "WHERE $condition";
        } elseif (is_array($condition)) {
            $arr = array();
            foreach ($condition as $f => $c) {
                if (preg_match('/^.*\.\d+$/', $f)) {
                    $fs = explode('.', $f);
                    $f = array_shift($fs);
                }
                if (in_array($f, $vcolumns)) {
                } elseif (array_key_exists($f, $columns)) {
                    $f = $columns[$f];
                } else {
                    // ignore
                    continue;
                }
                // condition isnot empty string
                if ($c !== '') {
                    $chip = $this->bindCondition($chip, $f, $c, $safe);
                }
            }
            if (!empty($chip)) {
                $chip = "WHERE $chip";
            } else {
                $chip = empty($safe) ? "WHERE 1 = 1" : "WHERE 1 = 0";
            }
        }
        return $chip;
    }
    /**
     * @param $chip where condition SQL chip
     * @param $f
     * @param $c
     *      数组:array($value, $operator, $join)
     *          $operator:
     *              '=','<>','>','<','<=','>=', 'BETWEEN', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN'
     *      标量:'1'
     * @param $safe
     */
    public function bindCondition($chip, $f, $val, $safe = true)
    {
        if (is_array($val) && !empty($val)) {
            $v = array_shift($val);
            $o = empty($val) ? '=' : array_shift($val);
            $j = empty($val) ? 'AND' : array_shift($val);
        } elseif (is_scalar($val)) {
            $v = $val;
            $o = '=';
            $j = 'AND';
        }
        if (!empty($chip)) {
            $chip .= " $j ";
        }
        switch (strtoupper($o)) {
            case 'BETWEEN':
                $v0 = array_shift($v);
                $v1 = empty($v) ? '0' : array_shift($v);
                $v0 = mysqli_real_escape_string($this->link, $v0);
                $v1 = mysqli_real_escape_string($this->link, $v1);
                $chip .= "(`$f` BETWEEN '$v0' AND '$v1')";
                break;
            case 'IN':
            case 'NOT IN':
                $v = (array) $v;
                foreach ($v as $k=>$val) {
                    $v[$k] = mysqli_real_escape_string($this->link, $val);
                }
                $v = implode("', '", $v);
                $chip .= "(`$f` $o ('$v'))";
                break;
            case 'LIKE':
            case 'NOT LIKE':
                if (is_scalar($v)) {
                    $v = strval($v);
                    $v = mysqli_real_escape_string($this->link, $v);
                    $chip .= "(`$f` $o '%$v%')";
                } else {
                    // TODO
                }
                break;
            case '=':
            case '<>':
            case '!=':
            case '<=':
            case '>=':
            case '<':
            case '>':
                $v = strval($v);
                $v = mysqli_real_escape_string($this->link, $v);
                $chip .= "(`$f` $o '$v')";
                break;
            default:
                $chip .= empty($safe) ? "(1 = 1)" : "(1 = 0)";
                break;
        }
        return $chip;
    }
    /**
     * make select order SQL chip
     */
    public function makeOrder($o)
    {
        $chip = '';
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        if (is_string($o)) {
            $chip = "ORDER BY $o";
        } elseif (is_array($o)) {
            $arr = array();
            foreach ($o as $f => $a) {
                $a = $a == 'ASC' ? $a : 'DESC';
                if (in_array($f, $vcolumns)) {
                    $arr[] = "`$f` $a";
                } elseif (array_key_exists($f, $columns)) {
                    $f = $columns[$f];
                    $arr[] = "`$f` $a";
                }
            }
            if (!empty($arr)) {
                $order = implode(', ', $arr);
                $chip = "ORDER BY $order";
            }
        }
        return $chip;
    }
    /**
     * make select limit SQL chip
     */
    public function makeLimit($l)
    {
        $chip = '';
        if (is_string($l)) {
            $chip = "LIMIT $l";
        } elseif (is_array($l)) {
            if (count($l) == 1) {
                $l0 = array_shift($l);
                $v0 = intval($l0);
                $v0 = $v0 < 0 ? 0 : $v0;
                $chip = "LIMIT $v0";
            } elseif (count($l) > 1) {
                $l0 = array_shift($l);
                $l1 = array_shift($l);
                $v0 = intval($l0);
                $v1 = intval($l1);
                $v0 = $v0 < 0 ? 0 : $v0;
                if ($v1 > 0) {
                    $chip = "LIMIT $v0, $v1";
                }
            }
        }
        return $chip;
    }
    /**
     * make update setter SQL chip
     */
    public function makeSetter($f, $v)
    {
        $chip = '';
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        $arr = array();
        if (empty($f)) {
            foreach ($v as $k => $val) {
                if (in_array($k, $vcolumns)) {
                    $val = mysqli_real_escape_string($this->link, $val);
                    $arr[] = "`$k` = '$val'";
                } elseif (array_key_exists($k, $columns)) {
                    $val = mysqli_real_escape_string($this->link, $val);
                    $arr[] = "`{$columns[$k]}` = '$val'";
                } else {
                    // ignore
                    continue;
                }
            }
        } else {
            $fs = Utility::isAssocArray($f) ? array_keys($f) : array_values($f);
            foreach ($v as $k => $val) {
                if (in_array($k, $vcolumns)) {
                    $fr = $k;
                    $fl = array_search($k, $columns);
                    // when that is the valid field
                    if (in_array($fl, $fs) || in_array($fr, $fs)) {
                        $val = mysqli_real_escape_string($this->link, $val);
                        $arr[] = "`$k` = '$val'";
                    }
                } elseif (array_key_exists($k, $columns)) {
                    $fl = $k;
                    $fr = $columns[$k];
                    // when that is the valid field
                    if (in_array($fl, $fs) || in_array($fr, $fs)) {
                        $val = mysqli_real_escape_string($this->link, $val);
                        $arr[] = "`{$columns[$k]}` = '$val'";
                    }
                } else {
                    // ignore
                    continue;
                }
            }
        }
        if (!empty($arr)) {
            $chip = implode(", ", $arr);
        }
        return $chip;
    }
    public function makeIncreaseSetter($f, $n)
    {
        $n = empty($n) ? 0 : intval($n);
        $chip = '';
        $model = $this->model;
        $columns = $this->model->columns();
        $vcolumns = array_values($columns);
        $arr = array();
        if (empty($f)) {
            return $chip;
        } elseif (in_array($f, $vcolumns)) {
            $arr[] = "`$f` = `$f` + $n";
        } elseif (array_key_exists($f, $columns)) {
            $arr[] = "`{$columns[$f]}` = `{$columns[$f]}` + $n";
        } else {
            // ignore
            continue;
        }
        if (!empty($arr)) {
            $chip = implode(", ", $arr);
        }
        return $chip;
    }

    public function freeResult()
    {
        if (!empty($this->result)) {
            mysqli_free_result($this->result);
        }
        return true;
    }
}
// PHP END
