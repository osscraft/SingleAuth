<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Model;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;

use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VUser;
use Dcux\SSO\Model\User;
use Dcux\SSO\Model\LdapUser;
use Dcux\SSO\Model\MysqlUser;
use stdClass;

class VStatUser extends VObject
{
    protected $id = 0;
    protected $uid = '';
    protected $date = '';
    protected $count = 0;
    protected $user = null;
    public function __construct()
    {
        $this->user = new stdClass;
        parent::__construct();
    }
    public function mapping()
    {
        return array(
            'uid' => 'username'
        );
    }
    public function rules()
    {
        return array(
                'id' => Component::TYPE_INTEGER,
                'uid' => Component::TYPE_STRING,
                'date' => Component::TYPE_DATE,
                'count' => Component::TYPE_INTEGER,
                'user' => array(Component::TYPE_FORMAT, array())
        );
    }
    public function format($val, $key, $option = array())
    {
        $ret = '';
        switch ($key) {
            case 'user':
                if ($val instanceof VUser) {
                    $ret = $val;
                } elseif ($val instanceof Model) {
                    $ret = VUser::parseByModel($val);
                    ;
                } elseif ((is_array($val) && !empty($val)) || (is_object($val) && !Utility::emptyObject($val))) {
                    $ret = VUser::parse($val);
                } else {
                    $ret = new stdClass;
                }
                break;
            default:
                break;
        }
        return $ret;
    }

    
    public static function parse($statUser)
    {
        $ret = parent::parse($statUser);
        // diff properties

        // pick user
        if (!empty($ret) && !Utility::emptyObject($ret)) {
            /*if(is_object($ret)) {
                $username = empty($statUser->username) ? '' : $statUser->username;
            } else if(is_array($statUser)) {
                $username = empty($statUser['username']) ? '' : $statUser['username'];
            }*/
            if (!empty($ret->uid)) {
                $ret->user = VUser::parse(VObject::$datapicker->pickUser($ret->uid));
            } else {
                $ret->user = new stdClass;
            }
        } else {
            $ret = new stdClass;
        }

        return $ret;
    }

    public static function parseSimple($statUser)
    {
        return parent::parse($statUser);
    }
}
// PHP END
