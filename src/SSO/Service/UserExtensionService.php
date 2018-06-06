<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Utility;

use Dcux\SSO\Model\UserExtension;

class UserExtensionService extends Service
{
    private $userExtension;
    public static function getInstance()
    {
        $instance = parent::getInstance();
        return $instance;
    }
    public function model()
    {
        $this->userExtension = UserExtension::getInstance();
        return $this->userExtension;
    }
    public static function updateLastLogin($uid, $client_id)
    {
        $instance=self::getInstance();
        $arr = array();
        $arr['uid'] = $uid;
        $arr['lastLogin'] = date('Y-m-d H:i:s');
        $arr['lastClientId'] = $client_id;
        $arr['lastIp'] = sprintf("%u", ip2long(Utility::ip()));
        $arr['lastOs'] = Utility::os();
        $arr['lastBrowser'] = Utility::browser();
        $arr['lastUa'] = Utility::ua();
        return $instance->replace($arr);
    }
}
