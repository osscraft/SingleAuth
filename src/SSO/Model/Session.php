<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\Core\Volatile;
use Lay\Advance\DB\DataBase;
use Lay\Advance\DB\Mysql;
use Lay\Advance\Util\Logger;

class Session extends Model implements Volatile
{
    protected $id = '';
    protected $data = '';
    protected $online = 0;
    protected $time = '';
    protected $expires = 0;
    protected static $database;
    public function cacher()
    {
        global $CFG;
        // 当需要保持会话且无延迟保持时不使用memcache
        if (!empty($CFG['mysql_session_keep']) && empty($CFG['mysql_session_delay'])) {
            return false;
        } else {
            $cacher = DataBase::factory('memcache');
            $cacher->setModel($this);
            return $cacher;
        }
    }
    public function lifetime()
    {
        global $CFG;
        return empty($CFG['mysql_session_lifetime']) ? 1800 : $CFG['mysql_session_lifetime'];
    }
    public function db()
    {
        if (empty(Session::$database)) {
            Session::$database = new Mysql();
            Session::$database->setModel($this);
            Session::$database->alter('session');
        }
        return Session::$database;
    }
    public function schema()
    {
        return 'sso';
    }
    public function table()
    {
        return 'session';
    }
    public function primary()
    {
        return 'id';
    }
    public function columns()
    {
        return array(
                'id' => 'id',
                'data' => 'data',
                'online' => 'online',
                'time' => 'time',
                'expires' => 'expires'
        );
    }
}
