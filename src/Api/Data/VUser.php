<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;

use Dcux\Api\Data\VObject;
use Dcux\SSO\Model\User;
use Dcux\SSO\Model\LdapUser;
use Dcux\SSO\Model\MysqlUser;

class VUser extends VObject
{
    protected $uid = '';
    protected $username = '';
    protected $role = '';
    public function rules()
    {
        return array(
                'uid' => Component::TYPE_STRING,
                'username' => Component::TYPE_STRING,
                'role' => Component::TYPE_STRING
        );
    }

    /**
     */
    public static function parseRole($role)
    {
        // TODO
        return $role;
    }
    /**
     * @param User|LdapUser|MysqlUser $user
     */
    public static function parseByModel($user)
    {
        if ($user instanceof User) {
            return self::parseByUser($user);
        } elseif ($val instanceof LdapUser) {
            return self::parseByLdapUser($user);
        } elseif ($val instanceof MysqlUser) {
            return self::parseByMysqlUser($user);
        }
        return new self;
    }
    /**
     * @param User $user
     */
    public static function parseByUser($user)
    {
        if ($user instanceof User) {
            return parent::parse($user->toStandard());
        } else {
            return new self;
        }
    }
    /**
     * @param LdapUser $user
     */
    public static function parseByLdapUser($user)
    {
        if ($user instanceof LdapUser) {
            return parent::parse($user->toStandard());
        } else {
            return new self;
        }
    }
    /**
     * @param MysqlUser $user
     */
    public static function parseByMysqlUser($user)
    {
        if ($user instanceof MysqlUser) {
            return parent::parse($user->toStandard());
        } else {
            return new self;
        }
    }
}
// PHP END
