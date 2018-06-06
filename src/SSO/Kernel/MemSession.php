<?php

namespace Dcux\SSO\Kernel;

use Memcache;
use Lay\Advance\Util\Logger;

class MemSession
{
    private static $instance = null;
    private function __construct()
    {
        $this->init();
    }
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new MemSession();
        }
        return self::$instance;
    }
    private $objs = array();
    private function init()
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($CFG['memcaches'] as $k => $v) {
                if (!empty($v['host']) && !empty($v['port'])) {
                    $obj = new Memcache();
                    $obj->connect($v['host'], $v['port']);
                    $this->objs[] = $obj;
                }
            }
        }
    }
    public function statSession()
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $stat = $obj->getStats();
                if ($stat) {
                    return $stat;
                }
            }
        } else {
            return array();
        }
    }
    public function loadSession()
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $session = $obj->get(session_id());
                if ($session) {
                    $_SESSION = $session;
                    return $session;
                }
            }
        } else {
            return $_SESSION;
        }
    }
    public function saveSession()
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $obj->set(session_id(), $_SESSION, 0, $CFG['memcache_lifetime']);
            }
        }
    }
    public function deleteSession()
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $obj->delete(session_id());
            }
        }
    }
    public function open()
    {
    }
    public function close()
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $obj->close();
            }
            return true;
        } else {
            return false;
        }
    }
    public function read($sessionId)
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $session = $obj->get($sessionId);
                if ($session) {
                    // $_SESSION = $session;
                    return $session;
                }
            }
            return false;
        } else {
            return false;
        }
    }
    public function write($sessionId, $data)
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $obj->set($sessionId, $data, MEMCACHE_COMPRESSED, $CFG['memcache_lifetime']);
            }
            return true;
        } else {
            return false;
        }
    }
    public function destroy($sessionId)
    {
        global $CFG;
        if ($CFG['use_memcache'] && class_exists('Memcache')) {
            foreach ($this->objs as $obj) {
                $obj->delete($sessionId);
            }
            return true;
        } else {
            return false;
        }
    }
    public function gc()
    {
        return true;
    }
}
