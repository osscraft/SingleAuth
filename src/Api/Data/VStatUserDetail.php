<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Model;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;

use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VUser;
use Dcux\Api\Data\VClient;
use Dcux\SSO\Model\User;
use Dcux\SSO\Model\Client;
use Dcux\SSO\Model\LdapUser;
use Dcux\SSO\Model\MysqlUser;
use stdClass;

class VStatUserDetail extends VObject
{
    protected $id = 0;
    protected $uid = '';
    protected $cid = '';
    protected $time = '';
    protected $ip = '';
    protected $os = '';
    protected $browser = '';
    protected $user = null;
    protected $client = null;
    public function __construct()
    {
        $this->user = new stdClass;
        $this->client = new stdClass;
        parent::__construct();
    }
    public function mapping()
    {
        return array(
            'uid' => 'username',
            'cid' => 'clientId'
        );
    }
    public function rules()
    {
        return array(
                'id' => Component::TYPE_INTEGER,
                'uid' => Component::TYPE_STRING,
                'cid' => Component::TYPE_STRING,
                'time' => Component::TYPE_DATETIME,
                'ip' => array(Component::TYPE_FORMAT, array()),
                'os' => Component::TYPE_STRING,
                'browser' => Component::TYPE_STRING,
                'user' => array(Component::TYPE_FORMAT, array()),
                'client' => array(Component::TYPE_FORMAT, array())
        );
    }
    public function format($val, $key, $option = array())
    {
        $ret = '';
        switch ($key) {
            case 'ip':
                if (is_numeric($val)) {
                    $ret = Utility::ntoip($val);
                }
                break;
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
            case 'client':
                if ($val instanceof VClient) {
                    $ret = $val;
                } elseif ($val instanceof Client) {
                    $ret = VClient::parseByClient($val);
                } elseif ((is_array($val) && !empty($val)) || (is_object($val) && !Utility::emptyObject($val))) {
                    $ret = VClient::parse($val);
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
            if (!empty($ret->uid)) {
                $ret->user = VUser::parse(VObject::$datapicker->pickUser($ret->uid));
            } else {
                $ret->user = new stdClass;
            }
            if (!empty($ret->cid)) {
                $ret->client = VClient::parse(VObject::$datapicker->pickClient($ret->cid));
            } else {
                $ret->client = new stdClass;
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
