<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;

use Dcux\SSO\Model\StatUser;

class StatUserService extends Service
{
    private $statUser;
    public static function getInstance()
    {
        $instance = parent::getInstance();
        return $instance;
    }
    public function model()
    {
        $this->statUser=StatUser::getInstance();
        return $this->statUser;
    }
    public static function addByDay($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            $username=$args['username'];
        } else {
            return false;
        }
        $date=date('Y-m-d');
        $ret = $instance->model()->query(array(), array('username'=>$username,'date'=>$date));
        if ($ret) {
            $id=$ret[0]['id'];
            $condition=array('count'=>$ret[0]['count']+1);
            return $instance->upd($id, $condition);
        } else {
            return $instance->add(array('username'=>$username,'count'=>1,'date'=>date('Y-m-d')));
        }
    }
}
