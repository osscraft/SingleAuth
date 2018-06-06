<?php

namespace Dcux\SSO\SDK;

use Dcux\SSO\SDK\SSOToOAuth2;
use Lay\Advance\Util\Logger;

class SSOClient extends SSOToOAuth2
{
    public $resourceURL = 'http://192.168.0.23/SSO/src/resource.php';
    public function __construct($clientId, $clientSecret, $access_token = null, $refresh_token = null)
    {
        global $CFG;
        $this->resourceURL = empty($CFG['SSO_RESOURCE_URL']) ? $this->resourceURL : $CFG['SSO_RESOURCE_URL'];
        parent::__construct($clientId, $clientSecret, $access_token, $refresh_token);
    }
    public function getUserInfo()
    {
        $resourceURL = $this->resourceURL;
        $access_token = $this->access_token;
        $params = array(
                'access_token' => $access_token
        );
        if (function_exists('curl_init')) {
            $response = $this->http($resourceURL, 'GET', $params);
        } else {
            $response = $this->fopen($resourceURL, 'GET', $params);
        }
        $result = json_decode($response, true);
        return $result;
    }
}
// PHP END
