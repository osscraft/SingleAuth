<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Util\Utility;

use Dcux\SSO\Model\LdapUser;
use Dcux\SSO\Kernel\Authorizable;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Service\MysqlUserService;
use Dcux\SSO\Service\LdapService;

class IdentifyService extends UserService implements Authorizable {
    protected $service;
    protected function __construct() {
        parent::__construct();
        $identify = App::get('identify_database', 'identify');
        if($identify == 'mysql') {
            $this->service = MysqlUserService::getInstance();
        } else {
            $this->service = LdapService::getInstance();
        }
    }
    public function verifyResourceOwner($username, $password, $resourcescope = array()) {
    	return $this->service->verifyResourceOwner($username, $password, $resourcescope);
    }
    public function getUser($username, $resourcescope = array()) {
    	return $this->service->getUser($username, $resourcescope);
    }
}
// PHP END