<?php
namespace Dcux\SSO\Model;

use Lay\Advance\Core\ModelLdap;
use Lay\Advance\DB\DataBase;
use Lay\Advance\Core\Volatile;

class LdapUser extends ModelLdap implements Volatile
{
    protected $uid = '';
    protected $username = '';
    protected $role = '';
    public function cacher()
    {
        $cacher = DataBase::factory('memcache');
        $cacher->setModel($this);
        return $cacher;
    }
    public function lifetime()
    {
        return 86400;
    }
    public function objectClass()
    {
        return array(
            'top', 'user'
        );
    }
    public function rules()
    {
        return array();
    }
    public function table()
    {
        $role = $this->role;
        switch ($role) {
            case '教师':
                return 'ou=teacher';
            case '学生':
                return 'ou=student';
            default:
                return 'ou=other';
        }
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
            'role' => 'role'
        );
    }
}
// PHP END
