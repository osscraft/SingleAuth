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

class LdapService extends UserService implements Authorizable
{
    public function model()
    {
        return $this->ldapUser;
    }
    public function verifyResourceOwner($username, $password, $resourcescope = array())
    {
        if (is_string($resourcescope)) {
            $resourcescope = array_map('trim', explode(',', $resourcescope));
        } elseif (!is_array($resourcescope)) {
            $resourcescope = array();
        }
        $ret = $this->model()->db()->verify($username, $password, $resourcescope);
        return empty($ret) ? false : $ret;
    }
    public function getUser($username, $resourcescope = array())
    {
        if (is_string($resourcescope)) {
            $resourcescope = array_map('trim', explode(',', $resourcescope));
        } elseif (!is_array($resourcescope)) {
            $resourcescope = array();
        }
        // old
        //$ret = $this->model()->db()->entry($username, $resourcescope);
        //$arr = $this->model()->db()->get($username);
        return $this->get($username);
    }
}
// PHP END
