<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\ModelUnique;
use Lay\Advance\DB\DataBase;
use Lay\Advance\Core\Volatile;

/**
 * 客户端类
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
class Client extends ModelUnique implements Volatile
{
    protected $id = 0;
    protected $clientId = '';
    protected $clientName = '';
    protected $clientDescribe = '';
    protected $clientType = '';
    protected $clientSecret = '';
    protected $redirectURI = '';
    protected $clientScope = '';
    protected $clientLocation = '';
    protected $clientLogoUri = '';
    protected $clientIsShow = 0;
    protected $clientVisible = 0;
    protected $clientOrderNum = 0;
    protected $tokenLifetime = 0;
    protected $owner = '';
    public function lifetime()
    {
        return 86400;
    }
    // 不使用缓存
    public function cacher()
    {
        $cacher = DataBase::factory('memcache');
        $cacher->setModel($this);
        return $cacher;
    }
    public function schema()
    {
        return 'sso';
    }
    public function table()
    {
        return 'clients';
    }
    public function primary()
    {
        return 'id';
    }
    public function unique()
    {
        return 'client_id';
        //return array('id', 'client_id');
    }
    public function columns()
    {
        return array(
                'id' => 'id',
                'clientName' => 'client_name',
                'clientDescribe' => 'client_describe',
                'clientId' => 'client_id',
                'clientType' => 'client_type',
                'clientSecret' => 'client_secret',
                'redirectURI' => 'redirect_uri',
                'clientScope' => 'scope',
                'clientLocation' => 'client_location',
                'clientLogoUri' => 'logo_uri',
                'clientIsShow' => 'is_show',
                'clientVisible' => 'visible',
                'clientOrderNum' => 'order_num',
                'tokenLifetime' => 'token_lifetime',
                'owner' => 'owner'
        );
    }
}
