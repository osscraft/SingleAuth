<?php

namespace Lay\Advance\Core;

use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Errode;
use ReflectionObject;
use ReflectionProperty;
use Iterator;
use ArrayAccess;
use stdClass;
use Exception;

/**
 */
abstract class Component implements ArrayAccess, Iterator {
    /**
     * 忽略类型的属性值
     * @var int
     */
    const TYPE_IGNORE = 0;
    /**
     * 字符串类型的属性值
     * @var int
     */
    const TYPE_STRING = 1;
    /**
     * 数值类型的属性值
     * @var int
     */
    const TYPE_NUMBER = 2;
    /**
     * 整数类型的属性值
     * @var int
     */
    const TYPE_INTEGER = 3;
    /**
     * 布尔类型的属性值
     * @var int
     */
    const TYPE_BOOLEAN = 4;
    /**
     * 日期时间类型的属性值
     * @var int
     */
    const TYPE_DATETIME = 5;
    /**
     * 日期类型的属性值
     * @var int
     */
    const TYPE_DATE = 6;
    /**
     * 时间类型的属性值
     * @var int
     */
    const TYPE_TIME = 7;
    /**
     * 浮点数类型的属性值
     * @var int
     */
    const TYPE_FLOAT = 8;
    /**
     * double类型的属性值
     * @var int
     */
    const TYPE_DOUBLE = 9;
    /**
     * 数组类型的属性值
     * @var int
     */
    const TYPE_ARRAY = 10;
    /**
     * 数组类型的属性值
     * @var int
     */
    const TYPE_PURE_ARRAY = 11;
    /**
     * 特定格式类型的属性值
     * @var int
     */
    const TYPE_DATEFORMAT = 12;
    /**
     * 指定值范围的属性值
     * @var int
     */
    const TYPE_ENUM = 13;
    /**
     * 其他类型的属性值
     * @var int
     */
    const TYPE_FORMAT = 14;
    /**
     * stdClass
     * @var int
     */
    const TYPE_STDCLASS = 15;
    /**
     * json object array
     * @var int
     */
    const TYPE_JSON_OBJECT = 16;
    /**
     * json array
     * @var int
     */
    const TYPE_JSON_ARRAY = 17;
    /**
     * all components' properties
     */
    protected static $properties = array();
    /**
     * 返回对象所有属性名的数组
     * @return array
     */
    //public abstract function properties();
    protected function properties() {
        $class = get_class($this);
        if(!isset(Component::$properties[$class])) {
            Component::$properties[$class] = get_object_vars($this);
        }
        //Logger::debug(VBasic::$properties);//exit;
        return Component::$properties[$class];
    }

    /**
     * 返回对象所有属性值规则
     * @return array
     */
    public abstract function rules();
    /**
     * 返回规则转换后的值
     * @return array
     */
    public abstract function format($val, $key, $option = array());

    /**
     * 清空对象所有属性值
     * @return Beanizable
     */
    public function restore() {
        // 恢复默认值
        foreach ($this->properties() as $name => $value) {
            $this->$name = $value;
        }
        return $this;
    }
    /**
     * 返回对象属性名对属性值的数组
     * @return array
     */
    public function toArray() {
        $ret = array();
        foreach ($this->properties() as $name => $def) {
            $ret[$name] = $this->_toArray($this->$name);
        }
        return $ret;
    }
    /**
     * 迭代返回对象属性名对属性值的数组
     * @param mixed $val            
     * @return mixed
     */
     protected function _toArray($val) {
        if(is_array($val)) {
            $var = array();
            foreach($val as $k => $v) {
                $var[$k] = $this->_toArray($v);
            }
            return $var;
        } else if(is_object($val) && method_exists($val, 'toArray')) {
            return $val->toArray();
        } else if(is_object($val)) {
            return $this->_toArray(get_object_vars($val));
        } else {
            return $val;
        }
    }
    /**
     * 返回对象转换为stdClass后的对象
     * @return stdClass
     */
    public function toStandard() {
        $ret = new stdClass();
        foreach ($this->properties() as $name => $value) {
            $ret->$name = $this->_toStandard($this[$name]);
        }
        return $ret;
    }
    /**
     * 迭代返回对象转换为stdClass后的对象
     * @param mixed $var            
     * @return mixed
     */
    protected function _toStandard($val) {
        if(is_array($val) && Utility::isAssocArray($val)) {
            $var = new stdClass();
            foreach($val as $k => $v) {
                $var->$k = $this->_toStandard($v);
            }
            return $var;
        } else if(is_array($val)) {
            $var = array();
            foreach($val as $k => $v) {
                $var[$k] = $this->_toStandard($v);
            }
            return $var;
        } else if($val instanceof Component) {
            return $val->toStandard();
        } else if(is_object($val)) {
            $var = new stdClass();
            foreach(get_object_vars($val) as $k => $v) {
                $var->$k = $this->_toStandard($v);
            }
            return $var;
            //return $this->_toStandard(get_object_vars($val));
        } else {
            return $val;
        }
    }
    /**
     * PHP 5.4
     * json serialize function
     * 
     * @return stdClass
     */
    public function jsonSerialize() {
        return $this->toStandard();
    }

    /**
     * 自动执行方法,即执行get和set方法
     *
     * @return mixed
     */
    public function __call($method, $arguments) {/*
        if (method_exists($this, $method)) {
            return (call_user_func_array(array (
                    $this,
                    $method 
            ), $arguments));
        } else {*/
        if (strtolower(substr($method, 0, 3)) === 'get') {
            // $name = strtolower(substr($method,3,1)).substr($method,4);
            $name = substr($method, 3);
            $name = strtolower(substr($name, 0, 1)) . substr($name, 1);
            return $this->$name;
        } else if (strtolower(substr($method, 0, 3)) === 'set') {
            // $name = strtolower(substr($method,3,1)).substr($method,4);
            $name = substr($method, 3);
            $name = strtolower(substr($name, 0, 1)) . substr($name, 1);
            $this->$name = $arguments[0];
        } else {
            throw new Exception("There is no method:" . $method . "( ) in class " . get_class($this));
        }
        //}
    }
    /**
     * 设置对象属性值的魔术方法
     * @param string $name 属性名
     * @param mixed $value 属性值
     * @return void
     */
    public final function __set($name, $value) {
        if(isset($this->$name)) {
            $rules = $this->rules();
            if(! empty($rules) && array_key_exists($name, $rules)) {
                switch($rules[$name]) {
                    case self::TYPE_IGNORE:
                        $this->$name = $value;
                        break;
                    case self::TYPE_STRING:
                        $this->$name = strval($value);
                        break;
                    case self::TYPE_NUMBER:
                        $this->$name = 0 + $value;
                        break;
                    case self::TYPE_INTEGER:
                        $this->$name = intval($value);
                        break;
                    case self::TYPE_BOOLEAN:
                        $this->$name = $value ? true : false;
                        break;
                    case self::TYPE_DATETIME:
                        $this->$name = !is_numeric($value) ? !is_string($value) ?$this->$name: date('Y-m-d H:i:s', strtotime($value)) : date('Y-m-d H:i:s', intval($value));
                        break;
                    case self::TYPE_DATE:
                        $this->$name = !is_numeric($value) ? !is_string($value) ?$this->$name: date('Y-m-d', strtotime($value)) : date('Y-m-d', intval($value));
                        break;
                    case self::TYPE_TIME:
                        $this->$name = !is_numeric($value) ? !is_string($value) ?$this->$name: date('H:i:s', strtotime($value)) : date('H:i:s', intval($value));
                        break;
                    case self::TYPE_FLOAT:
                        $this->$name = floatval($value);
                        break;
                    case self::TYPE_DOUBLE:
                        $this->$name = doubleval($value);
                        break;
                    case self::TYPE_ARRAY:
                        $this->$name = !is_array($value) ?$this->$name: $value;
                        break;
                    case self::TYPE_PURE_ARRAY:
                        $this->$name = !is_array($value) ?$this->$name: Utility::toPureArray($value);
                        break;
                    case self::TYPE_STDCLASS:
                        $this->$name = empty($value) || !is_object($value) ? new stdClass : $value;
                        break;
                    case self::TYPE_JSON_OBJECT:
                        if(is_string($value)) {
                            $value = json_decode($value, true);
                        }
                        if(empty($value) || !is_array($value)) {
                            $value = new stdClass;
                        }
                        $this->$name = Utility::isAssocArray($value) ? $value : new stdClass;
                    case self::TYPE_JSON_ARRAY:
                        if(is_string($value)) {
                            $value = json_decode($value, true);
                        }
                        if(empty($value) || !is_array($value)) {
                            $value = array();
                        }
                        $this->$name = Utility::isAssocArray($value) ? array_values($value) : $value;
                        break;
                    default:
                        if(is_array($rules[$name]) && $pure = Utility::toPureArray($rules[$name])) {
                            if(count($pure) > 1 && self::TYPE_DATEFORMAT == $pure[0]) {
                                $this->$name = !is_numeric($value) ? !is_string($value) ?: date($pure[1], strtotime($value)) : date($pure[1], intval($value));
                            } else if(count($pure) > 1 && self::TYPE_ENUM == $pure[0]) {
                                $this->$name = !in_array($value, (array)$pure[1]) ?: $value;
                            } else if(count($pure) > 1 && self::TYPE_FORMAT == $pure[0]) {
                                $this->$name = $this->format($value, $name, (array)$pure[1]);
                            }
                        }
                        break;
                }
            } else {
                $this->$name = $value;
            }
        } else {
            Logger::error('no property ' . $name . ' in ' . get_class($this));
        }
    }
    /**
     * 设置对象属性值的魔术方法
     * @param string $name 属性名
     * @return void
     */
    public final function __get($name) {
        if(isset($this->$name)) {
            return $this->$name;
        } else {
            Logger::error('There is no property:' . $name . ' in class:' . get_class($this));
        }
    }
    /**
     * 检测属性是否设置
     *
     * @param string $name
     *            属性名
     * @return boolean
     */
    public final function __isset($name) {
        $properties = $this->properties();
        if(Utility::isAssocArray($properties)) {
            return array_key_exists($name, $properties);
        } else {
            return array_key_exists($name, array_flip($properties));
        }
    }
    /**
     * 无法将某个属性去除
     *
     * @param string $name
     *            属性名
     * @return void
     */
    public final function __unset($name) {
    	return false;
    }
    /**
     * 返回序列化后的字符串
     *
     * @return string
     */
    public final function __toString() {
        return serialize($this);
    }
    /**
     * @see Iterator::current()
     */
    public function current() {
        return current($this);
    }
    /**
     * @see Iterator::next()
     */
    public function next() {
        return next($this);
    }
    /**
     * @see Iterator::key()
     */
    public function key() {
        return key($this);
    }
    /**
     * @see Iterator::valid()
     */
    public function valid() {
        return key($this) !== null;
    }
    /**
     * @see Iterator::rewind()
     */
    public function rewind() {
        return reset($this);
    }
    /**
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($index) {
        return isset($this->$index);
    }
    /**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($index) {
        return $this->$index;
    }
    /**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($index, $value) {
        $this->$index = $value;
    }
    /**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($index) {
        return false;
    }
}
// PHP END