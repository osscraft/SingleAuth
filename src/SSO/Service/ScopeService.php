<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Action;
use Lay\Advance\DB\Uniqueness;

use Dcux\SSO\Model\Client;
use Dcux\SSO\Service\StatService;

class ScopeService extends Service
{
    public function model()
    {
        return false;
    }
    /**
     * 过滤掉不合法的scope
     *
     * @param string|array $scope
     * @return array
     */
    public function filter($scope = array())
    {
        if (empty($scope)) {
            $tmp = array();
            $tmp['uid'] = 'uid';
            $tmp['username'] = 'username';
            $tmp['role'] = 'role';
            $str = implode(',', array_keys($tmp));
            $arr = array_values($tmp);
            $scope = array(
                    $str,
                    $arr
            );
        } elseif (is_string($scope)) {
            $scope = trim($scope) ? array_map('trim', explode(',', $scope)) : '';
            $scope = $this->filter($scope);
        } elseif (is_array($scope)) {
            $tmp = $scope;
            if (Utility::isPureArray($scope)) {
                $str = implode(',', array_values($tmp));
            } else {
                $str = implode(',', array_keys($tmp));
            }
            $arr = array_values($tmp);
            $scope = array(
                    $str,
                    $arr
            );
        }
        return $scope;
    }
}
