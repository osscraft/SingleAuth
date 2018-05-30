<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\Core\Volatile;
use Lay\Advance\DB\DataBase;
use Lay\Advance\Core\ModelUnique;
use Lay\Advance\Util\Logger;
/**
 * 令牌类
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
class Token extends ModelUnique implements Volatile {
    protected $oauthToken = '';
    protected $clientId = '';
    protected $expires = 0;
    protected $scope = '';
    protected $username = '';
    protected $type = 0;
    public function cacher() {
        $cacher = DataBase::factory('memcache');
        $cacher->setModel($this);
        return $cacher;
    }
    public function lifetime() {
        global $CFG;
        if(empty($this->expires)) {
            if($this->type == $CFG['refresh_token_type']) {
                return $CFG['refresh_token_lifetime'];
            } else {
                return $CFG['access_token_lifetime'];
            }
        } else {
            return $this->expires - time();
        }
    }
	
	public function unique(){
		return array('client_id', 'username');
	}
    public function schema() {
        return 'sso';
    }
    public function table() {
        return 'tokens';
    }
    public function primary() {
        return 'oauth_token';
    }
    public function columns() {
        return array (
                'oauthToken' => 'oauth_token',
                'clientId' => 'client_id',
                'expires' => 'expires',
                'scope' => 'scope',
                'username' => 'username',
                'type' => 'type' 
        );
    }
}
?>
