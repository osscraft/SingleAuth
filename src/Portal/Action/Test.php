<?php

namespace Dcux\Portal\Action\Portal;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Core\Model;
use Lay\Advance\Core\ModelLdap;
use Lay\Advance\DB\DataBase;
use Lay\Advance\DB\Memcache;
use Lay\Advance\Core\Volatile;

use Dcux\Portal\Kernel\PAction;
use Dcux\SSO\Manager\ClientManager;
use Dcux\SSO\Service\ClientService;

// Action中调用Service层，不直接调用Model层

class Test extends PAction
{
    public function onGet()
    {
        $data =array();
        /*$model = StatUser::getInstance();
        $g = $model->get(1);
        //$u = $model->upd(54, array('username'=>'liaiyong', 'success' => 0));
        $m = StatUserDetail::getInstance();
        //$d = $m->get('');
        $l = $m->lists(array(1,18));
        $user = User::getInstance();
        $get = $user->get('liaiyong');
        //$verify = $user->verify('liaiyong', 'dcuxpasswd');
        $user->uid = 'lay';
        $user->role = '其他';
        $user->username = 'lay';
        $user->setPassword('1qa2ws3ed');
        $del = $user->del('lay');
        $add = $user->add($user->toArray());
        $user->setPassword('dcuxpasswd');
        $upd = $user->upd('lay', array());*/
        $cs = ClientService::getInstance();
        $get = $cs->get(19);
        //$unique = $cs->getByUnique(array('id' => 19, 'client_id'=>'sso_client_23'));
        $unique = $cs->getByUnique('sso_client_23');
        //$data['g'] = $g;
        //$data['verify'] = $verify;
        $data['get'] = $get;
        $data['unique'] = $unique;
        /*$data['add'] = $add;
        $data['del'] = $del;
        $data['upd'] = $upd;*/
        //$data['list'] = $l;

        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
    public function onPost()
    {
        $this->onGet();
    }
}
class User extends ModelLdap
{
    public function objectClass()
    {
        return array(
            'top', 'user'
        );
    }
    public function properties()
    {
        return array(
            'uid' => '',
            'username' => '',
            'role' => ''
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
class StatUser extends Model implements Volatile
{
    public function lifetime()
    {
        return 10000;
    }
    public function cacher()
    {
        $mem = DataBase::factory('memcache');
        $mem->setModel($this);
        return $mem;
    }
    public function properties()
    {
        return array(
            'id' => 0,
            'username' => '',
            'date' => '',
            'count' => 0
        );
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
        return 'stat_user';
    }
    public function primary()
    {
        return 'id';
    }
    public function columns()
    {
        return array(
            'id' => 'id',
            'username' => 'username',
            'date' => 'date',
            'count' => 'count'
        );
    }
}

class StatUserDetail extends Model
{
    public function cacher()
    {
        $mem = DataBase::factory('memcache');
        $mem->setModel($this);
        return $mem;
    }
    public function properties()
    {
        return array(
            'id' => 0,
            'time' => '',
            'username' => '',
            'clientId' => '',
            'success' => 0,
            'ip' => 0,
            'os' => '',
            'browser' => ''
        );
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
        return 'stat_user_detail';
    }
    public function primary()
    {
        return 'id';
    }
    public function columns()
    {
        return array(
            'id' => 'id',
            'time' => 'time',
            'username' => 'username',
            'clientId' => 'client_id',
            'success' => 'success',
            'ip' => 'ip',
            'os' => 'os',
            'browser' => 'browser'
        );
    }
}
// PHP END
