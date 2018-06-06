<?php
namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\Core\Identification;
use Lay\Advance\DB\Mysql;
use Lay\Advance\DB\DataBase;

class MysqlUser extends Model
{
    protected $uid = '';
    protected $username = '';
    protected $password = '';
    protected $role = '';
    protected static $db;
    /*public function cacher() {
        $cacher = DataBase::factory();
        $cacher->setModel($this);
        return $cacher;
    }*/
    public function db()
    {
        if (empty(self::$db)) {
            self::$db = new Mysql();
            self::$db->setModel($this);
            self::$db->alter('identify');
        }
        return self::$db;
    }
    public function rules()
    {
        return array();
    }
    public function schema()
    {
        return 'sso';
    }
    public function table()
    {
        return 'users';
    }
    public function primary()
    {
        return 'uid';
    }
    public function columns()
    {
        return array(
            'uid' => 'uid',
            'username' => 'username',
            'password' => 'password',
            'role' => 'role'
        );
    }
}
// PHP END
