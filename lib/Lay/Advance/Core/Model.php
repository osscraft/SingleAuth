<?php
namespace Lay\Advance\Core;

use Lay\Advance\Core\App;
use Lay\Advance\Core\SingleComponent;
use Lay\Advance\Core\Modelizable;
//use Lay\Advance\Core\Asynchronous;
use Lay\Advance\DB\DataBase;
use Lay\Advance\DB\Cacher;
use Lay\Advance\DB\Engine;
use Lay\Advance\DB\Querying;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;

abstract class Model extends SingleComponent implements Modelizable
{
    //use Singleton;
    const E_GET = 'model:event:get';
    const E_ADD = 'model:event:add';
    const E_DELETE = 'model:event:delete';
    const E_UPDATE = 'model:event:update';
    const E_COUNT = 'model:event:count';
    const E_REPLACE = 'model:event:replace';
    /**
     * 构造方法
     * @return Model
     */
    final protected function __construct()
    {
        foreach ($this->properties() as $pro => $def) {
            $this->$pro = $def;
        }
        $this->listen();
    }
    protected function listen()
    {
        $class = get_class($this);
        App::$_event->listen($class, self::E_GET, array($this, 'onGet'));
        App::$_event->listen($class, self::E_ADD, array($this, 'onAdd'));
        App::$_event->listen($class, self::E_DELETE, array($this, 'onDel'));
        App::$_event->listen($class, self::E_UPDATE, array($this, 'onUpd'));
        App::$_event->listen($class, self::E_COUNT, array($this, 'onCount'));
        App::$_event->listen($class, self::E_REPLACE, array($this, 'onReplace'));
    }
    /**
     * get后触发
     */
    public function onGet($ret, $id)
    {
        $cacher = $this->cacher();
        if (!empty($cacher) && !empty($ret)) {
            $cacher->upd($id, $ret);
        }
    }
    /**
     * add后触发
     */
    public function onAdd($ret, $info, $use_last_id = true)
    {
        $cacher = $this->cacher();
        if (!empty($cacher) && !empty($ret)) {
            $cacher->add($info, $use_last_id);
        }
    }
    /**
     * del后触发
     */
    public function onDel($ret, $id, $info = array())
    {
        $cacher = $this->cacher();
        if (!empty($cacher) && !empty($ret)) {
            $cacher->del($id);
        }
    }
    /**
     * upd后触发
     */
    public function onUpd($ret, $id, $info)
    {
        $cacher = $this->cacher();
        if (!empty($cacher) && !empty($ret)) {
            $cacher->del($id);
        }
    }
    /**
     * count后触发
     */
    public function onCount($ret, $info)
    {
    }
    /**
     * replace后触发
     */
    public function onReplace($ret, $info)
    {
        if (!empty($ret)) {
            $cacher = $this->cacher();
            $pk = $this->primary();
            $columns = $this->columns();
            $pkl = array_search($pk, $columns);
            if (!empty($info[$pk])) {
                $id = $info[$pk];
            } elseif (!empty($info[$pkl])) {
                $id = $info[$pkl];
            } elseif (is_numeric($ret)) {
                $id = $ret;
            }
            if (!empty($cacher) && !empty($id)) {
                $cacher->del($id);
            }
        }
    }

    /**
     * @see Modelizable::rules()
     */
    public function rules()
    {
        return array();
    }
    /**
     * 兼容
     */
    public function pk()
    {
        return $this->primary();
    }

    /**
     * @see Component::format()
     */
    public function format($val, $key, $options = array())
    {
        return $val;
    }
    /**
     * 返回对象属性名对属性值的数组
     * @return array
     */
    final public function toArray()
    {
        $ret = array();
        foreach ($this->properties() as $pro => $def) {
            if (isset($this->$pro)) {
                $ret[$pro] = $this->$pro;
            }
        }
        return $ret;
    }
    /**
     * 返回对象转换为stdClass后的对象
     * @return stdClass
     */
    public function toStandard()
    {
        $ret = new stdClass();
        foreach ($this->properties() as $pro => $value) {
            $ret->$pro = $this->$pro;
        }
        return $ret;
    }
    /**
     * 兼容,
     * 对应表名
     * @return string
     */
    public function toTable()
    {
        return $this->table();
    }
    /**
     * 兼容,
     * 属性名转换为字段名
     * @return string
     */
    public function toField($property)
    {
        $columns = $this->columns();
        return array_key_exists($property, $columns) ? $columns[$property] : false;
    }
    /**
     * 兼容,
     * 所有字段名
     * @return array
     */
    public function toFields()
    {
        $columns = $this->columns();
        return array_values($columns);
    }
    public function toValues()
    {
        $values = array();
        foreach ($this->columns() as $p => $f) {
            $values[$f] = $this->$p;
        }
        return $values;
    }
    /**
     * 兼容,
     * 将数据库中查询到的多条记录赋到对应对象数组中
     */
    public function rowsToEntities($rows)
    {
        $entities = array();
        $className = get_class($this);
        if (is_array($rows)) {
            foreach ($rows as $k => $row) {
                if (is_array($row)) {
                    $bean = new $className();
                    $return = $bean->rowToEntity($row);
                    $entities[] = $bean;
                }
            }
        }
        return $entities;
    }
    /**
     * 兼容,
     * 将数据库中查询到的记录赋到本对象中
     */
    public function rowToEntity($row)
    {
        $columns = $this->columns();
        if (is_array($row) && $columns) {
            foreach ($this->properties() as $k => $v) {
                $f = $columns[$k];
                if (isset($row[$f])) {
                    $this->$k = $row[$f];
                }
            }
        }
        return $this;
    }
    /**
     * 兼容,
     */
    public function rowsToArray($rows)
    {
        $arrs = array();
        $className = get_class($this);
        if (is_array($rows)) {
            foreach ($rows as $k => $row) {
                if (is_array($row)) {
                    $model = new $className();
                    $arr = $model->rowToArray($row);
                    $arrs[] = $arr;
                }
            }
        }
        return $arrs;
    }
    /**
     * 兼容,
     */
    public function rowToArray($row)
    {
        $arr = array();
        if (is_array($row)) {
            $model = $this->rowToEntity($row);
            $arr = $this->toArray();
        }
        return $arr;
    }
    /**
     * 兼容,
     */
    public function build($args = 0)
    {
        $args = empty($args) ? $_REQUEST : $args;
        $columns = $this->columns();
        foreach ($this->properties() as $p => $v) {
            $f = $columns[$p];
            if (isset($args[$p])) {
                $this->$p = $args[$p];
            } elseif (isset($args[$f])) {
                $this->$p = $args[$f];
            }
        }
    }

    /**
     * 数据存储数据库
     * @return DataBase
     */
    public function db()
    {
        $db = DataBase::factory();
        $db->setModel($this);
        return $db;
    }
    /**
     * 数据缓存数据库
     * @return Cacher
     */
    public function cacher()
    {
        return false;
    }
    /**
     * 数据搜索引擎数据库
     * @return Engine
     */
    public function engine()
    {
        return false;
    }

    final public function get($id, $fields = array())
    {
        $cacher = $this->cacher();
        if (!empty($cacher)) {
            $ret = $cacher->get($id, $fields);
        }
        if (empty($ret)) {
            $ret = $this->db()->get($id, $fields);
            App::$_event->fire(get_class($this), self::E_GET, array($ret, $id));
        }
        return empty($ret) ? false : $ret;
    }
    final public function add(array $info, $use_last_id = true)
    {
        $ret = $this->db()->add($info, $use_last_id);
        App::$_event->fire(get_class($this), self::E_ADD, array($ret, $info, $use_last_id));
        return $ret;
    }
    final public function del($id)
    {
        $info = $this->db()->get($id);
        if (!empty($info)) {
            $ret = $this->db()->del($id);
        }
        $ret = empty($ret) ? false : $ret;
        App::$_event->fire(get_class($this), self::E_DELETE, array($ret, $id, $info));
        return $ret;
    }
    final public function upd($id, array $info)
    {
        $data = $this->db()->get($id);
        if (!empty($data)) {
            $ret = $this->db()->upd($id, $info);
            App::$_event->fire(get_class($this), self::E_UPDATE, array($ret, $id, $data));
            return $ret;
        } else {
            return false;
        }
    }
    final public function count(array $info = array())
    {
        $ret = $this->db()->count($info);
        App::$_event->fire(get_class($this), self::E_COUNT, array($ret, $info));
        return $ret;
    }
    final public function replace(array $info = array())
    {
        $ret = $this->db()->replace($info);
        //$this->onReplace($ret, $info);
        App::$_event->fire(get_class($this), self::E_REPLACE, array($ret, $info));
        return $ret;
    }

    /**
     * get list of objects by serveral ids
     */
    final public function lists(array $ids = array())
    {
        $cacher = $this->cacher();
        if (empty($cacher) && !empty($ids)) {
            $db = $this->db();
            $pk = $this->primary();
            $columns = $this->columns();
            $fields = array_values($columns);
            $condition = array();
            $condition[$pk] = array($ids, 'IN');
            if ($db instanceof Querying) {
                $ret = $db->select($fields, $condition);
            }
            return empty($ret) ? false : $ret;
        } elseif (!empty($ids)) {
            $arr = array();
            foreach ($ids as $id) {
                $ret = $this->get($id);
                if (!empty($ret)) {
                    $arr[] = $ret;
                }
            }
            return empty($arr) ? false :$arr;
        } else {
            return false;
        }
    }
    /**
     * get list of objects by serveral query conditions
     */
    final public function query($fields = array(), array $condition = array(), $order = array(), $limit = array())
    {
        if (!empty($condition)) {
            $db = $this->db();
            //$columns = $this->columns();
            //$fields = array_values($columns);
            if ($db instanceof Querying) {
                $ret = $db->select($fields, $condition, $order, $limit);
            }
            return empty($ret) ? false : $ret;
        } else {
            return false;
        }
    }
    /**
     * get list of objects by serveral search conditions.
     * use engine.
     */
    final public function search($fields = array(), array $condition = array(), $order = array(), $limit = array())
    {
        $engine = $this->engine();
        if (empty($engine) && !empty($condition)) {
            return $this->query($fields, $condition, $order, $limit);
        } elseif (!empty($condition)) {
            $ret = $engine->search($fields, $condition, $order, $limit);
            return empty($ret) ? false : $ret;
        } else {
            return false;
        }
    }


    /**
     * 自身数据保存数据库
     * @param $unpk
     */
    final public function save($use_last_id = true)
    {
        // TODO
        $pk = $this->primary();
        $colums = $this->columns();
        $pkl = array_search($pk, $columns);
        $data = $this->toArray();
        if (!empty($data[$pk])) {
            $id = $data[$pk];
            unset($data[$pk]);
            return $this->upd($id, $data);
        } elseif (!empty($data[$pkl])) {
            $id = $data[$pkl];
            unset($data[$pkl]);
            return $this->upd($id, $data);
        } else {
            $last_id = $this->add($data, $use_last_id);
            if ($use_last_id && $last_id) {
                $this->$pkl = $last_id;
            }
            return $last_id;
        }
    }
    /**
     * 通过自身数据创建
     * @param $unpk
     */
    final public function create($unpk = true, $use_last_id = true)
    {
        $pk = $this->primary();
        $colums = $this->columns();
        $pkl = array_search($pk, $columns);
        $data = $this->toArray();
        if ($unpk) {
            //去除Primary Key
            unset($data[$pk]);
            unset($data[$pkl]);
        }
        $last_id = $this->add($data, $use_last_id);
        if ($use_last_id && $last_id) {
            $this->$pk = $last_id;
        }
        return $last_id;
    }
    /**
     * 通过自身数据更新
     * @param array $fields 指定更新某些字段
     */
    final public function update(array $fields = array())
    {
        $pk = $this->primary();
        $columns = $this->columns();
        $vcolumns = array_values($columns);
        $pkl = array_search($pk, $columns);
        $data = $this->toArray();
        if (!empty($data[$pkl])) {
            $id = $data[$pkl];
            unset($data[$pkl]);//去除Primary Key
            if (empty($fields)) {
                return $this->upd($id, $data);
            } else {
                $arr = array();
                foreach ($fields as $f) {
                    if (array_key_exists($f, $columns)) {
                        $arr[$columns[$f]] = $data[$f];
                    } elseif (in_array($f, $vcolumns)) {
                        $pro = array_search($f, $columns);
                        $arr[$f] = $data[$pro];
                    }
                }
                if (empty($arr)) {
                    return false;
                } else {
                    return $this->upd($id, $arr);
                }
            }
        } else {
            return false;
        }
    }
    /**
     * 通过自身数据删除
     */
    final public function delete()
    {
        $pk = $this->primary();
        $columns = $this->columns();
        $vcolumns = array_values($columns);
        $pkl = array_search($pk, $columns);
        $data = $this->toArray();
        if (!empty($data[$pkl])) {
            return $this->del($data[$pkl]);
        } else {
            return false;
        }
    }
    public function makeTable($schema_prefix = true)
    {
        $schema = $this->schema();
        $tables = $this->table();
        $chip = '';
        if (is_array($tables)) {
            // 多
            $chip = $this->_makeTable($schema, $tables, $schema_prefix);
        } else {
            // 单表时
            $table = $tables;// 设置表名
            $chip = !empty($schema) && !empty($schema_prefix) ? "`$schema`.`$table`" : "`$table`";
        }
        return $chip;
    }
    protected function _makeTable($schema, $tables = array(), $schema_prefix = true)
    {
        //var_dump(array($schema, $tables, $schema_prefix));exit;
        if (!empty($schema) && !empty($schema_prefix)) {
            $tables = array_map(function ($table) use ($schema) {
                return "`$schema`.`$table`";
            }, $tables);
        } else {
            $tables = array_map(function ($table) use ($schema) {
                return "`$table`";
            }, $tables);
        }
        return $this->implodeTable($tables);
    }
    // TODO
    // 自行实现
    public function implodeTable($tables)
    {
        return '';
    }
    //
    protected function _makeFields($table, $columns, $fields = array(), $as = true, $table_prefix = false, $mark = ', ')
    {
        $chip = '';
        $chip_arr = array();
        $mark = empty($mark) ? ', ' : $mark;
        $tp = empty($table_prefix) ? false : true;
        $fields = empty($fields) ? $columns : $fields;
        $vcolumns = array_values($columns);
        if (is_array($fields) && Utility::isAssocArray($fields)) {
            foreach ($fields as $k => $v) {
                if (in_array($k, $vcolumns)) {
                    $asf = array_search($k, $columns);
                    $notp = empty($as) ? "`$k`" : "`$k` AS `$asf`";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } elseif (array_key_exists($k, $columns)) {
                    $asf = $k;
                    $notp = empty($as) ? "`{$columns[$k]}`" : "`{$columns[$k]}` AS `$asf`";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } else {
                    // ignore
                    continue;
                }
            }
        } elseif (is_array($fields)) {
            foreach ($fields as $v) {
                if (in_array($v, $vcolumns)) {
                    $asf = array_search($v, $columns);
                    $notp = empty($as) ? "`$v`" : "`$v` AS `$asf`";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } elseif (array_key_exists($v, $columns)) {
                    $asf = $v;
                    $notp = empty($as) ? "`{$columns[$v]}`" : "`{$columns[$v]}` AS `$asf`";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } else {
                    // ignore
                    continue;
                }
            }
        }
        if (!empty($chip_arr)) {
            $chip = implode($mark, $chip_arr);
        }
        return $chip;
    }
    /**
     * 生成SQL语句的字段部分
     */
    public function makeFields($fields = array(), $as = true, $table_prefix = false, $mark = ', ')
    {
        $tables = $this->table();
        $mcolumns = $this->columns();
        $mark = empty($mark) ? ', ' : $mark;
        $tp = empty($table_prefix) ? false : true;
        $chip = '';
        if (is_string($fields)) {
            $chip = $fields;
        } elseif (is_array($tables)) {
            $fields = empty($fields) ? $mcolumns : $fields;
            $chip_arr = array();
            // 多表时
            foreach ($tables as $key => $table) {
                $columns = empty($mcolumns[$table]) ? array() : $mcolumns[$table];
                $_fields = empty($fields[$table]) ? array() : $fields[$table];
                //var_dump($columns);exit;
                //if($table == 'oapcr_event_pool') {var_dump($columns);exit;}
                $chip_arr[] = $this->_makeFields($table, $columns, $_fields, $as, true, $mark);
            }
            if (!empty($chip_arr)) {
                $chip = implode($mark, $chip_arr);
            }
        } else {
            // 单表时
            $table = $tables;// 设置表名
            $columns = $mcolumns;// 只有单表的字段映射
            $fields = empty($fields) ? '*' : $fields;
            $chip = $this->_makeFields($table, $columns, $fields, $as, $tp, $mark);
        }
        return $chip;
    }
    /**
     * 生成INSERT SQL语句中赋值部分
     */
    public function makeValues($fields, $values, $link)
    {
        $chip = '';
        $tables = $this->table();
        $columns = $this->columns();
        $vcolumns = array_values($columns);
        $chip_arr = array();
        if (is_array($tables)) {
            // 多表不支持
        } else {
            if (empty($fields)) {
                foreach ($values as $k => $val) {
                    if (in_array($k, $vcolumns)) {
                        $chip_arr[] = mysqli_real_escape_string($link, $val);
                    } elseif (array_key_exists($k, $columns)) {
                        $chip_arr[] = mysqli_real_escape_string($link, $val);
                    } else {
                        // ignore
                        continue;
                    }
                }
            } else {
                $fs = Utility::isAssocArray($fields) ? array_keys($fields) : array_values($fields);
                foreach ($values as $k => $val) {
                    if (in_array($k, $vcolumns)) {
                        $fr = $k;
                        $fl = array_search($k, $columns);
                        // when that is the valid field
                        if (in_array($fl, $fs) || in_array($fr, $fs)) {
                            $chip_arr[] = mysqli_real_escape_string($link, $val);
                        }
                    } elseif (array_key_exists($k, $columns)) {
                        $fl = $k;
                        $fr = $columns[$k];
                        // when that is the valid field
                        if (in_array($fl, $fs) || in_array($fr, $fs)) {
                            $chip_arr[] = mysqli_real_escape_string($link, $val);
                        }
                    } else {
                        // ignore
                        continue;
                    }
                }
            }
        }
        if (!empty($chip_arr)) {
            $chip = "'" . implode("', '", $chip_arr) . "'";
        }
        return $chip;
    }

    /**
     * 生成SELECT SQL语句中查询条件部分
     */
    public function makeCondition($condition, $link = null, $table_prefix = false, $safe = true)
    {
        $tables = $this->table();
        $mcolumns = $this->columns();
        $fields = empty($fields) ? $mcolumns : $fields;
        $tp = empty($table_prefix) ? false : true;
        $chip = '';
        if (is_string($condition)) {
            $chip = "WHERE $condition";
        } elseif (is_array($tables)) {
            // 多表时
            if (is_array($condition)) {
                $arr = array();
                foreach ($condition as $table => $cond) {
                    if (preg_match('/^.*\.\d+$/', $table)) {
                        $ts = explode('.', $table);
                        $table = array_shift($ts);
                    }
                    // $table 表字段映射
                    $columns = empty($mcolumns[$table]) ? array() : $mcolumns[$table];
                    $vcolumns = array_values($columns);
                    if (is_array($cond)) {
                        foreach ($cond as $f => $c) {
                            if (preg_match('/^.*\.\d+$/', $f)) {
                                $fs = explode('.', $f);
                                $f = array_shift($fs);
                            }
                            if (in_array($f, $vcolumns)) {
                            } elseif (array_key_exists($f, $columns)) {
                                $f = $columns[$f];
                            } elseif (is_numeric($f) && is_string($c)) {
                                $chip .= $c;
                            } else {
                                // ignore
                                continue;
                            }
                            // condition isnot empty string
                            if ($c !== '') {
                                $chip = $this->bindCondition($chip, $f, $c, $link, true, $table, $safe);
                            }
                        }
                    } elseif (is_numeric($table) && is_string($cond)) {
                        $chip .= $cond;
                    }
                }
            }
            if (!empty($chip)) {
                $chip = "WHERE $chip";
            } else {
                $chip = empty($safe) ? "WHERE 1 = 1" : "WHERE 1 = 0";
            }
        } else {
            // 单表时
            $table = $tables;// 设置表名
            $columns = $mcolumns;// 只有单表的字段映射
            $chip = $this->_makeCondition($table, $columns, $condition, $link, $tp, $safe);
        }
        return $chip;
    }
    protected function _makeCondition($table, $columns, $condition, $link = null, $table_prefix = false, $safe = true)
    {
        $chip = '';
        $tp = empty($table_prefix) ? false : true;
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
                if (is_numeric($f) && is_string($c)) {
                    $chip .= $c;
                    continue;
                } elseif (in_array($f, $vcolumns)) {
                } elseif (array_key_exists($f, $columns)) {
                    $f = $columns[$f];
                } else {
                    // ignore
                    continue;
                }
                // condition isnot empty string
                if ($c !== '') {
                    $chip = $this->bindCondition($chip, $f, $c, $link, $tp, $table, $safe);
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
     *          $join:
     *              'AND', 'OR', ''
     *      标量:'1'
     * @param $safe
     */
    public function bindCondition($chip, $f, $val, $link = null, $table_prefix = false, $table = '', $safe = true)
    {
        $tp = empty($table_prefix) ? false : true;
        if (is_array($val) && !empty($val)) {
            $v = array_shift($val);
            $o = empty($val) ? '=' : array_shift($val);
            $j = empty($val) ? 'AND' : array_shift($val);
            $fn = empty($val) ? '' : array_shift($val);
        } elseif (is_scalar($val)) {
            $v = $val;
            $o = '=';
            $j = 'AND';
            $fn = '';
        }
        if (!empty($chip)) {
            $trimchip = rtrim($chip);
            if (!preg_match('/.*\($/', $trimchip)) {
                $chip .= " $j ";
            }
        }
        switch (strtoupper($o)) {
            case 'BETWEEN':
                // 不支持FUNCTION
                $v0 = array_shift($v);
                $v1 = empty($v) ? '0' : array_shift($v);
                $v0 = empty($link) ? mysql_escape_string($v0) : mysqli_real_escape_string($link, $v0);
                $v1 = empty($link) ? mysql_escape_string($v1) : mysqli_real_escape_string($link, $v1);
                $notp = "`$f` BETWEEN '$v0' AND '$v1'";
                $chip .= $tp ? "`$table`.$notp" : "$notp";
                break;
            case 'IN':
            case 'NOT IN':
                // 不支持FUNCTION
                $v = (array) $v;
                foreach ($v as $k=>$val) {
                    $v[$k] = empty($link) ? mysql_escape_string($val) : mysqli_real_escape_string($link, $val);
                }
                $v = implode("', '", $v);
                $notp = "`$f` $o ('$v')";
                $chip .= $tp ? "`$table`.$notp" : "$notp";
                break;
            case 'LIKE':
            case 'NOT LIKE':
                // 不支持FUNCTION
                if (is_scalar($v)) {
                    $v = strval($v);
                    $v = empty($link) ? mysql_escape_string($v) : mysqli_real_escape_string($link, $v);
                    $notp = "`$f` $o '%$v%'";
                    $chip .= $tp ? "`$table`.$notp" : "$notp";
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
                $v = empty($link) ? mysql_escape_string($v) : mysqli_real_escape_string($link, $v);
                $notp = "`$f` $o '$v'";
                $chip .= $tp ? "`$table`.$notp" : "$notp";
                break;
            case 'FN':
                // TODO
                // 支持FUNCTION 未实现
                if (is_string($fn) && !empty($fn)) {
                    $fn = str_replace('?', $v, $fn);
                } else {
                }
                $chip .= empty($safe) ? "1 = 1" : "1 = 0";
                break;
            default:
                $chip .= empty($safe) ? "1 = 1" : "1 = 0";
                break;
        }
        return $chip;
    }
    /**
     * 生成SELECT SQL语句中查询排序部分
     */
    public function makeOrder($order, $table_prefix = false)
    {
        $chip = '';
        $tp = empty($table_prefix) ? false : true;
        $tables = $this->table();
        $mcolumns = $this->columns();
        if (is_string($order)) {
            $chip = "ORDER BY $order";
        } elseif (is_array($tables)) {
            if (is_array($order)) {
                $chip_arr = array();
                foreach ($order as $table => $o) {
                    if (preg_match('/^.*\.\d+$/', $table)) {
                        $ts = explode('.', $table);
                        $table = array_shift($ts);
                    }
                    // $table 表字段映射
                    $columns = empty($mcolumns[$table]) ? array() : $mcolumns[$table];
                    $chip_arr[] = $this->_makeOrder($table, $columns, $o, true);
                }
                if (!empty($chip_arr)) {
                    $order = implode(', ', $chip_arr);
                    $chip = "ORDER BY $order";
                }
            }
        } else {
            $table = $tables;// 设置表名
            $columns = $mcolumns;// 只有单表的字段映射
            $order = $this->_makeOrder($table, $columns, $order, $tp);
            if (!empty($order)) {
                $chip = "ORDER BY $order";
            }
        }
        return $chip;
    }
    protected function _makeOrder($table, $columns, $o, $table_prefix = false)
    {
        $chip = '';
        $tp = empty($table_prefix) ? false : true;
        $vcolumns = array_values($columns);
        if (is_string($o)) {
            $chip = "$o";
        } elseif (is_array($o)) {
            $chip_arr = array();
            foreach ($o as $f => $a) {
                $a = $a == 'ASC' ? $a : 'DESC';
                if (in_array($f, $vcolumns)) {
                    $notp = "`$f` $a";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } elseif (array_key_exists($f, $columns)) {
                    $f = $columns[$f];
                    $notp = "`$f` $a";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                }
            }
            if (!empty($chip_arr)) {
                $chip = implode(', ', $chip_arr);
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
     * 生成SELECT SQL语句中查询排序部分
     */
    public function makeSetter($f, $v, $link, $table_prefix = false)
    {
        $chip = '';
        $tables = $this->table();
        $mcolumns = $this->columns();
        $tp = empty($table_prefix) ? false : true;

        if ((is_string($f) && empty($f)) || (is_string($v) && !empty($v))) {
            $chip = "$order";
        } elseif (is_array($tables)) {
            // 多表不支持
        } else {
            $table = $tables;// 设置表名
            $columns = $mcolumns;// 只有单表的字段映射
            $chip = $this->_makeSetter($table, $columns, $f, $v, $link, $tp);
        }
        return $chip;
    }
    /**
     * make update setter SQL chip
     */
    protected function _makeSetter($table, $columns, $f, $v, $link = null, $table_prefix = false)
    {
        $chip = '';
        $vcolumns = array_values($columns);
        $tp = empty($table_prefix) ? false : true;
        $chip_arr = array();
        if (empty($f) && is_array($v)) {
            foreach ($v as $k => $val) {
                if (in_array($k, $vcolumns)) {
                    $val = empty($link) ? mysql_escape_string($val) : mysqli_real_escape_string($link, $val);
                    $notp = "`$k` = '$val'";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } elseif (array_key_exists($k, $columns)) {
                    $val = empty($link) ? mysql_escape_string($val) : mysqli_real_escape_string($link, $val);
                    $notp = "`{$columns[$k]}` = '$val'";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } else {
                    // ignore
                    continue;
                }
            }
        } elseif (is_array($v)) {
            $fs = Utility::isAssocArray($f) ? array_keys($f) : array_values($f);
            foreach ($v as $k => $val) {
                if (in_array($k, $vcolumns)) {
                    $fr = $k;
                    $fl = array_search($k, $columns);
                    // when that is the valid field
                    if (in_array($fl, $fs) || in_array($fr, $fs)) {
                        $val = empty($link) ? mysql_escape_string($val) : mysqli_real_escape_string($link, $val);
                        $notp = "`$k` = '$val'";
                        $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                    }
                } elseif (array_key_exists($k, $columns)) {
                    $fl = $k;
                    $fr = $columns[$k];
                    // when that is the valid field
                    if (in_array($fl, $fs) || in_array($fr, $fs)) {
                        $val = empty($link) ? mysql_escape_string($val) : mysqli_real_escape_string($link, $val);
                        $notp = "`{$columns[$k]}` = '$val'";
                        $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                    }
                } else {
                    // ignore
                    continue;
                }
            }
        }
        if (!empty($chip_arr)) {
            $chip = implode(", ", $chip_arr);
        }
        return $chip;
    }
    public function makeIncreaseSetter($f, $n, $table_prefix = false)
    {
        $chip = '';
        $tables = $this->table();
        $mcolumns = $this->columns();
        $tp = empty($table_prefix) ? false : true;

        if (is_array($tables)) {
            // 多表不支持
        } else {
            $table = $tables;// 设置表名
            $columns = $mcolumns;// 只有单表的字段映射
            $chip = $this->_makeIncreaseSetter($table, $columns, $f, $n, $tp);
        }
        return $chip;
    }
    protected function _makeIncreaseSetter($table, $columns, $f, $n, $table_prefix = false)
    {
        $n = empty($n) ? 0 : intval($n);
        $chip = '';
        $vcolumns = array_values($columns);
        $tp = empty($table_prefix) ? false : true;
        $chip_arr = array();
        if (empty($f)) {
            // 不做
        } elseif (in_array($f, $vcolumns)) {
            $chip_arr[] = $tp ? "`$table`.`$f` = `$table`.`$f` + $n" : "`$f` = `$f` + $n";
        } elseif (array_key_exists($f, $columns)) {
            $chip_arr[] = $tp ? "`$table`.`{$columns[$f]}` = `$table`.`{$columns[$f]}` + $n" : "`{$columns[$f]}` = `{$columns[$f]}` + $n";
        } else {
            // ignore
            // continue;
        }
        if (!empty($chip_arr)) {
            $chip = implode(", ", $chip_arr);
        }
        return $chip;
    }

    /**
     * make select GROUP BY SQL chip
     * only string supported
     */
    public function makeGroup($group, $table_prefix = false)
    {
        $chip = '';
        $tp = empty($table_prefix) ? false : true;
        $tables = $this->table();
        $mcolumns = $this->columns();
        if (is_string($group)) {
            $chip = "GROUP BY $group";
        } elseif (is_array($tables)) {
            if (is_array($group)) {
                $chip_arr = array();
                foreach ($group as $table => $o) {
                    if (preg_match('/^.*\.\d+$/', $table)) {
                        $ts = explode('.', $table);
                        $table = array_shift($ts);
                    }
                    // $table 表字段映射
                    $columns = empty($mcolumns[$table]) ? array() : $mcolumns[$table];
                    $chip_arr[] = $this->_makeGroup($table, $columns, $o, true);
                }
                if (!empty($chip_arr)) {
                    $group = implode(', ', $chip_arr);
                    $chip = "GROUP BY $group";
                }
            }
        } else {
            $table = $tables;// 设置表名
            $columns = $mcolumns;// 只有单表的字段映射
            $group = $this->_makeGroup($table, $columns, $group, $tp);
            if (!empty($group)) {
                $chip = "GROUP BY $group";
            }
        }
        return $chip;
        if (empty($group)) {
            return '';
        } elseif (is_array($group)) {
        } else {
            return "GROUP BY $group";
        }
    }
    protected function _makeGroup($table, $columns, $g, $table_prefix = false)
    {
        $chip = '';
        $tp = empty($table_prefix) ? false : true;
        $vcolumns = array_values($columns);
        if (is_string($g)) {
            $chip = "$g";
        } elseif (is_array($g)) {
            $chip_arr = array();
            foreach ($g as $f) {
                if (in_array($f, $vcolumns)) {
                    $notp = "`$f`";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                } elseif (array_key_exists($f, $columns)) {
                    $f = $columns[$f];
                    $notp = "`$f`";
                    $chip_arr[] = $tp ? "`$table`.$notp" : $notp;
                }
            }
            if (!empty($chip_arr)) {
                $chip = implode(', ', $chip_arr);
            }
        }
        return $chip;
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
}
// PHP END
