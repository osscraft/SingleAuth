<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\Core\Volatile;
use Lay\Advance\DB\DataBase;

/**
 * 授权类
 *
 * @category
 *
 * @package classes
 * @author liaiyong <liaiyong@dcux.com>
 * @version 1.0
 * @copyright 2005-2012 dcux Inc.
 * @link http://www.dcux.com
 *
 */
class AuthCode extends Model implements Volatile
{
    protected $code = '';
    protected $clientId = '';
    protected $redirectURI = '';
    protected $username = '';
    protected $expires = 0;
    protected $scope = '';
    public function cacher()
    {
        $cacher = DataBase::factory('memcache');
        $cacher->setModel($this);
        return $cacher;
    }
    public function lifetime()
    {
        global $CFG;
        return $CFG['auth_code_lifetime'];
    }
    /*public function properties() {
        return array (
                'code' => '',
                'clientId' => '',
                'redirectURI' => '',
                'username' => '',
                'expires' => 0,
                'scope' => ''
        );
    }*/
    public function schema()
    {
        return 'sso';
    }
    public function table()
    {
        return 'auth_codes';
    }
    public function primary()
    {
        return 'code';
    }
    public function columns()
    {
        return array(
                'code' => 'code',
                'clientId' => 'client_id',
                'redirectURI' => 'redirect_uri',
                'username' => 'username',
                'expires' => 'expires',
                'scope' => 'scope'
        );
    }
}
