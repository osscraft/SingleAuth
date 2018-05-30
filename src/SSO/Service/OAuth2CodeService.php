<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Action;

use Dcux\SSO\Model\AuthCode;
use Dcux\SSO\OAuth2\OAuth2;

class OAuth2CodeService extends Service {
	private $oauth2Code;
    public function model() {
        return AuthCode::getInstance();
    }
    protected function __construct() {
    	parent::__construct();
    	$this->oauth2Code = AuthCode::getInstance();
    }

    public function gen($user, $client, $scope = '') {
        global $CFG;
        $username = $user['uid'];
        $client_id = $client['clientId'];
        $redirect_uri = $client['redirectURI'];
        $code = OAuth2::generateCode();
        $scope = empty($scope) ? $client['scope'] : $scope;
        $expires = time() + $CFG['auth_code_lifetime'];
        // 没有任务计划时执行删除已过期授权码
        if(empty($CFG['cron_open'])) {
            $this->clean();
        }
        if(!empty($username) && !empty($client_id) && !empty($redirect_uri)) {
	        $authCodeArray['code'] = $code;
	        $authCodeArray['clientId'] = $client_id;
	        $authCodeArray['redirectURI'] = $redirect_uri;
	        $authCodeArray['expires'] = $expires;
	        $authCodeArray['scope'] = $scope;
	        $authCodeArray['username'] = $username;
	        $ret = $this->add($authCodeArray);
        }
        return empty($ret) ? false : $code;
    }
    public function valid($code, $client) {
    	$oauth2CodeArr = $this->get($code);
    	// TODO
    }
    /**
     * 清除过期授权码
     */
    public function clean() {
        $field1 = $this->model()->toField('expires');
        $ret = $this->model()->db()->delete($field1 . ' < UNIX_TIMESTAMP()');
        return empty($ret) ? false : true;
    }
}
// PHP END