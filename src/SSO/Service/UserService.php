<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Paging;

use Dcux\SSO\Model\User;
use Dcux\SSO\Model\LdapUser;
use Dcux\SSO\Model\MysqlUser;
use Dcux\SSO\Model\UserElection;
use Dcux\SSO\Model\UserExtension;
use Dcux\SSO\Model\Client;

class UserService extends Service
{
    protected $user;
    protected $userElection;
    protected $userExtension;
    protected $mysqlUser;
    protected $ldapUser;
    protected $client;
    protected function __construct()
    {
        parent::__construct();
        $this->userElection = UserElection::getInstance();
        $this->userExtension = UserExtension::getInstance();
        $this->user = User::getInstance();
        $this->mysqlUser = MysqlUser::getInstance();
        $this->ldapUser = LdapUser::getInstance();
        $this->client = Client::getInstance();
    }
    public function model()
    {
        return $this->user;
    }

    // static 兼容旧版本
    public static function read($args = '')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            $condition=$args;
            if ($condition['uid']) {
                return $instance->get($condition['uid']);
            }
        } else {
            $condition=array();
            return $instance->getAll();
        }
        return $instance->model()->query(array(), $condition);
    }
    public static function counts($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            //return $args;
            $condition=$args;
        } else {
            return false;
        }
        return $instance->count($condition);
    }
    public static function delete($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            if ($args['uid']) {
                $id=$args['uid'];
                return $instance->del($id);
            }
        } else {
            return false;
        }
    }
    public static function create($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            $condition=$args;
        } else {
            return false;
        }
        return $instance->add($condition);
    }
    public static function update($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            $id=$args['uid'];
            if ($id) {
                $condition=$args;
                return $instance->upd($id, $condition);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public static function readUserPaging()
    {
        $p = new Paging();
        return $p->build($_REQUEST);
    }
    public static function readUserTotal($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readUserPaging();
        $ret = self::getInstance()->count();
        return empty($ret) ? 0 : $ret;
    }
    public static function readUserList($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readUserPaging();
        return self::getInstance()->getUserListPaging(array(), array('id' => 'ASC'), $paging->toLimit());
    }

    //
    public function getUserListPaging($condition = array(), $order = array(), $limit = array())
    {
        $ret = $this->model()->db()->select(array(), $condition, $order, $limit, false);
        return empty($ret) ? array() : $ret;
    }
    public function getUserListAll($condition = array(), $order = array())
    {
        $ret = $this->model()->db()->select(array(), $condition, $order, array(), false);
        return empty($ret) ? array() : $ret;
    }

    /**
     * parse role type
     */
    public function parseRole($role)
    {
        $role = strtolower($role);
        switch ($role) {
            case 'teacher':
            case '教师':
            case 1:
                $role = 1;
                break;
            case 'student':
            case '学生':
            case 2:
                $role = 2;
                break;
            case 'other':
            case '其他':
            case 3:
                $role = 3;
                break;
            default:
                $role = 0;
                break;
        }
        return $role;
    }
    public function parseLdapRole($role)
    {
        $role = strtolower($role);
        switch ($role) {
            case 'teacher':
            case '教师':
            case 1:
                $role = '教师';
                break;
            case 'student':
            case '学生':
            case 2:
                $role = '学生';
                break;
            case 'other':
            case '其他':
            case 3:
                $role = '其他';
                break;
            default:
                $role = '其他';
                break;
        }
        return $role;
    }
    public function deparseUser($user)
    {
        if (isset($user['role'])) {
            $user['role'] = $this->parseLdapRole($user['role']);
        }
        return $user;
    }
}
// PHP END
