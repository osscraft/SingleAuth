<?php

namespace Dcux\Core;

use RuntimeException;

// 单例模式
abstract class Singleton {
    protected static $_singletonStack = array ();
    protected function __construct() {
    }
    public function __clone() {
        throw new RuntimeException('Cloning ' . get_called_class() . ' is not allowed');
    }
    public static function getInstance() {
        $classname = get_called_class();
        if (empty(self::$_singletonStack[$classname])) {
            self::$_singletonStack[$classname] = new $classname();
        }
        return self::$_singletonStack[$classname];
    }
}

// PHP END