<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Paging;
use Lay\Advance\Util\Logger;

use Dcux\SSO\Model\User;
use Dcux\SSO\Model\LdapUser;
use Dcux\SSO\Model\MysqlUser;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Kernel\Authorizable;

class MysqlUserService extends UserService implements Authorizable
{
    /*protected $user;
    protected $mysqlUser;
    protected $userService;
    protected function __construct() {
        parent::__construct();
        $this->user = User::getInstance();
        $this->mysqlUser = MysqlUser::getInstance();
        $this->userService = UserService::getInstance();
    }*/
    public function model()
    {
        return $this->mysqlUser;
    }
    //
    public function getUser($uid, $scope = array())
    {
        $scope = is_string($scope) ? array_map('trim', explode(',', $scope)) : $scope;
        $ret = $this->model()->db()->select($scope, array('uid' => $uid), array(), array(1));
        if (empty($ret)) {
            return false;
        } else {
            return $this->deparseUser($ret[0]);
        }
    }
    public function verifyResourceOwner($uid, $password, $scope = array())
    {
        $scope = is_string($scope) ? array_map('trim', explode(',', $scope)) : $scope;
        // 96e79218965eb72c92a549dd5a330112
        $ret = $this->model()->db()->select($scope, array('uid' => $uid, 'password' => md5($password)), array(), array(1));
        if (empty($ret)) {
            return false;
        } else {
            return $this->deparseUser($ret[0]);
        }
    }
    /*protected function deparse($user) {
        if(isset($user['role'])) {
            switch ($user['role']) {
                case '1':
                    $user['role'] = '教师';
                    break;
                case '2':
                    $user['role'] = '学生';
                    break;
                default:
                    $user['role'] = '其他';
                    break;
            }
        }
        return $user;
    }*/
}
// PHP END
