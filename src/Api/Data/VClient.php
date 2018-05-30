<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Model;
use Lay\Advance\Util\Utility;

use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VUser;
use Dcux\SSO\Model\Client;
use stdClass;

class VClient extends VObject {
    //protected $id = 0;
    protected $cid = '';
    protected $clientName = '';
    protected $clientDescribe = '';
    protected $clientType = '';
    //protected $clientSecret = '';
    //protected $redirectURI = '';
    //protected $clientScope = '';
    protected $clientLocation = '';
    protected $clientLogoUri = '';
    //protected $clientIsShow = 0;
    //protected $clientVisible = 0;
    //protected $clientOrderNum = 0;
    //protected $tokenLifetime = 0;
    protected $ownerUid = '';
    protected $ownerUser = null;
    public function __construct() {
        $this->ownerUser = new stdClass;
        parent::__construct();
    }
    public function mapping() {
        return array(
            'cid' => 'clientId',
            'ownerUid' => 'owner'
        );
    }
    public function rules() {
    	return array(
                //'id' => Component::TYPE_INTEGER,
                'cid' => Component::TYPE_STRING,
                'clientName' => Component::TYPE_STRING,
                'clientDescribe' => Component::TYPE_STRING,
                'clientType' => Component::TYPE_STRING,
                //'clientSecret' => Component::TYPE_STRING,
                'redirectURI' => Component::TYPE_STRING,
                'clientScope' => Component::TYPE_STRING,
                'clientLocation' => Component::TYPE_STRING,
                'clientLogoUri' => Component::TYPE_STRING,
                'clientIsShow' => Component::TYPE_INTEGER,
                'clientVisible' => Component::TYPE_INTEGER,
                'clientOrderNum' => Component::TYPE_INTEGER,
                'tokenLifetime' => Component::TYPE_INTEGER,
                'ownerUid' => Component::TYPE_STRING,
                'ownerUser' => array(Component::TYPE_FORMAT, array())
    	);
    }
    public function format($val, $key, $option = array()) {
        $ret = '';
        switch ($key) {
            case 'ownerUser':
                if($val instanceof VUser){
                    $ret = $val;
                } else if($val instanceof Model) {
                    $ret = VUser::parseByModel($val);;
                } else if((is_array($val) && !empty($val)) || (is_object($val) && !Utility::emptyObject($val))) {
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

    public static function parse($client) {
        $ret = parent::parse($client);
        // diff properties

        // pick owner user
        if(!empty($ret) && !Utility::emptyObject($ret)) {
            if(!empty($ret->ownerUid)) {
                $ret->ownerUser = VUser::parse(VObject::$datapicker->pickUser($ret->ownerUid));
            } else {
                $ret->ownerUser = new stdClass;
            }
        }

        return $ret;
    }
    public static function parseSimple($statuser) {
        return parent::parse($statuser);
    }
    public static function parseByClient($client) {
        if($client instanceof Client) {
            return parent::parse($client->toStandard());
        }
        return new self;
    }
}
// PHP END