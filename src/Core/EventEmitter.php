<?php

namespace Dcux\Core;

use Dcux\Core\App;
use Dcux\Core\Singleton;
use Dcux\Util\Logger;
use Dcux\Util\Utility;

class EventEmitter extends Singleton
{
    // use Singleton;
    protected $listen = array();
    protected $subscribe = array();
    public function initialize()
    {
    }
    public function listen($object, $event, $callback, $params = array())
    {
        $key = $this->keyOf($object);
        $this->listen[$key][$event][] = $callback;
        $this->listen[$key]['__params__'][$event][] = empty($params) || !is_array($params) ? array() : $params;
    }
    public function subscribe($class, $event, $callback, $params = array())
    {
        $class = strtolower(ltrim($class, '\\'));
        $this->subscribe[$class][$event][] = $callback;
        $this->subscribe[$class]['__params__'][$event][] = empty($params) || !is_array($params) ? array() : $params;
    }
    public function fire($object, $event, array $args = null)
    {
        $fire = 0; // 回调次数
        if (! $this->listen && ! $this->subscribe) {
            return $fire;
        }
        $key = $this->keyOf($object);
        if (isset($this->listen[$key][$event])) {
            foreach ($this->listen[$key][$event] as $k => $callback) {
                $params = $this->listen[$key]['__params__'][$event][$k];
                $args ? call_user_func_array($callback, array_merge($params, $args)) : call_user_func($callback);
                $fire ++;
            }
        }
        if (! $this->subscribe || !is_object($object)) {
            return $fire;
        }
        $class = strtolower(get_class($object));
        if (! isset($this->subscribe[$class][$event])) {
            return $fire;
        }
        // 订阅回调参数
        // 第一个参数是事件对象
        // 第二个参数是事件参数
        $args = $args ? array(
                $object,
                $args
        ) : array(
                $object
        );
        foreach ($this->subscribe[$class][$event] as $callback) {
            $params = $this->subscribe[$class]['__params__'][$event][$k];
            call_user_func_array($callback, array_merge($params, $args));
            $fire ++;
        }
        return $fire;
    }
    public function clear($object, $event = null)
    {
        $key = $this->keyOf($object);
        if ($event === null) {
            unset($this->listen[$key]);
        } else {
            unset($this->listen[$key][$event]);
        }
    }
    protected function keyOf($obj)
    {
        return is_object($obj) ? spl_object_hash($obj) : $obj;
    }
}

// PHP END
