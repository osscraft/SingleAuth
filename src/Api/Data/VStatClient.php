<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Model;
use Lay\Advance\Util\Utility;

use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VClient;
use Dcux\SSO\Model\Client;
use stdClass;

class VStatClient extends VObject
{
    protected $id = 0;
    protected $cid = '';
    protected $date = '';
    protected $count = 0;
    protected $client = null;
    public function __construct()
    {
        $this->client = new stdClass;
        parent::__construct();
    }
    public function mapping()
    {
        return array(
            'cid' => 'clientId'
        );
    }
    public function rules()
    {
        return array(
                'id' => Component::TYPE_INTEGER,
                'cid' => Component::TYPE_STRING,
                'date' => Component::TYPE_DATE,
                'count' => Component::TYPE_INTEGER,
                'client' => array(Component::TYPE_FORMAT, array())
        );
    }
    public function format($val, $key, $option = array())
    {
        $ret = '';
        switch ($key) {
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

    
    public static function parse($statClient)
    {
        if (empty($statClient)) {
            $ret = new stdClass;
        } else {
            $ret = parent::parse($statClient);
        }
        // diff properties

        // pick client
        if ($ret && !Utility::emptyObject($ret)) {
            /*if(is_object($statClient)) {
                $clientId = empty($statClient->clientId) ? '' : $statClient->clientId;
            } else if(is_array($statClient)) {
                $clientId = empty($statClient['clientId']) ? '' : $statClient['clientId'];
            }*/
            if (!empty($ret->cid)) {
                $ret->client = VClient::parse(VObject::$datapicker->pickClient($ret->cid));
            } else {
                $ret->client = new stdClass;
            }
        }

        return $ret;
    }
    public static function parseSimple($statuser)
    {
        return parent::parse($statuser);
    }
}
// PHP END
