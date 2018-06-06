<?php
namespace Dcux\Core;

use Dcux\Core\App;
use Dcux\Core\SingleComponent;
use Dcux\Core\Modelizable;
//use Dcux\Core\Asynchronous;
use Dcux\DB\DataBase;
use Dcux\DB\Cacher;
use Dcux\DB\Engine;
use Dcux\DB\Querying;
use Dcux\Util\Logger;

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
    final public function __construct()
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
    public function onAdd($ret, $info)
    {
        $cacher = $this->cacher();
        if (!empty($cacher) && !empty($ret)) {
            $cacher->add($info);
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
    final public function add(array $info)
    {
        $ret = $this->db()->add($info);
        App::$_event->fire(get_class($this), self::E_ADD, array($ret, $info));
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
    final public function save()
    {
        // TODO
        $pk = $this->primary();
        $colums = $this->columns();
        $pkl = array_search($pk, $columns);
        $data = $this->toArray();
        if (!empty($data[$pkl])) {
            $id = $data[$pkl];
            unset($data[$pkl]);
            return $this->upd($id, $data);
        } else {
            $last_id = $this->add($data);
            if ($last_id) {
                $this->$pkl = $last_id;
            }
            return $last_id;
        }
    }
    /**
     * 通过自身数据创建
     * @param $unpk
     */
    final public function create($unpk = true)
    {
        $pk = $this->primary();
        $colums = $this->columns();
        $pkl = array_search($pk, $columns);
        $data = $this->toArray();
        if ($unpk) {
            //去除Primary Key
            unset($data[$pkl]);
        }
        $last_id = $this->add($data);
        if ($last_id) {
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
}
// PHP END
