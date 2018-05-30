<?php

namespace Dcux\SSO\SDK;

use Lay\Advance\Util\Logger;

/**
 * PHP SDK for SSO (using OAuth2)
 *
 * @author liaiyong <liaiyong@dcux.com>
 */

/**
 * SSO OAuth 认证类(OAuth2)
 *
 * 授权机制说明请大家参考OAuth官方
 *
 * @package
 *
 * @author liaiyong
 * @version 1.0
 */
class SSOToOAuth2 {
    /**
     *
     * @ignore
     *
     */
    public $clientId;
    /**
     *
     * @ignore
     *
     */
    public $clientSecret;
    /**
     *
     * @ignore
     *
     */
    public $access_token;
    /**
     *
     * @ignore
     *
     */
    public $refresh_token;
    /**
     * Set timeout default.
     *
     * @ignore
     *
     */
    public $timeout = 30;
    /**
     * Set connect timeout.
     *
     * @ignore
     *
     */
    public $connectTimeout = 30;
    /**
     * Verify SSL Cert.
     *
     * @ignore
     *
     */
    public $sslVerifyPeer = FALSE;
    public $authorizeURL = '';
    public $accessTokenURL = '';
    public $logoutURL = '';
    public function __construct($clientId, $clientSecret, $access_token = NULL, $refresh_token = NULL) {
        global $CFG;
        $this->authorizeURL = empty($CFG['SSO_AUTHORIZE_URL']) ? $this->authorizeURL : $CFG['SSO_AUTHORIZE_URL'];
        $this->accessTokenURL = empty($CFG['SSO_TOKEN_URL']) ? $this->accessTokenURL : $CFG['SSO_TOKEN_URL'];
        $this->logoutURL = empty($CFG['SSO_LOGOUT_URL']) ? $this->logoutURL : $CFG['SSO_LOGOUT_URL'];
        
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }
    /**
     * authorize接口
     *
     * @param string $url
     *            授权后的回调地址
     * @param string $response_type
     *            支持的值包括 code 和token 默认值为code
     * @param string $state
     *            用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
     * @return array
     */
    public function getAuthorizeURL($url, $response_type = 'code', $state = '1q2w3e') {
        $params = array ();
        $params['client_id'] = $this->clientId;
        $params['redirect_uri'] = $url;
        $params['response_type'] = $response_type;
        $params['state'] = $state;
        return $this->authorizeURL . "?" . http_build_query($params);
    }
    
    /**
     * access_token接口
     *
     * @param string $type
     *            请求的类型,可以为:code, token
     * @param array $keys
     *            其他参数：
     *            - 当$type为code时： array('code'=>..., 'redirectURI'=>...)
     *            - 当$type为token时： array('refresh_token'=>...)
     * @return array
     */
    public function getAccessToken($type = 'code', $keys) {
        $params = array ();
        $params['client_id'] = $this->clientId;
        $params['client_secret'] = $this->clientSecret;
        if ($type === 'token') {
            $params['grant_type'] = 'refresh_token';
            $params['refresh_token'] = $keys['refresh_token'];
        } else if ($type === 'code') {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $keys['code'];
            $params['redirect_uri'] = $keys['redirect_uri'];
        }
        if(function_exists('curl_init')) {
            $response = $this->http($this->accessTokenURL,'GET',$params);
        } else {
            $response = $this->fopen($this->accessTokenURL, 'GET', $params);
        }
        $token = json_decode($response, true);
        
        if (is_array($token) && ! isset($token['error'])) {
            $this->access_token = $token['access_token'];
            $this->refresh_token = $token['refresh_token'];
        }
        return $token;
    }
    public function getLogoutURL($uri = '') {
        $params = array ();
        $params['access_token'] = $this->access_token;
        $params['refresh_token'] = $this->refresh_token;
        $params['redirect_uri'] = $uri;
        return $this->logoutURL . "?" . http_build_query($params);
    }
    public function logout() {
        $params = array ();
        $access_token = $this->access_token;
        $refresh_token = $this->refresh_token;
        $params['access_token'] = $access_token;
        $params['refresh_token'] = $refresh_token;
        if(function_exists('curl_init')) {
            $response = $this->http($this->logoutURL,'GET',$params);
        } else {
            $response = $this->fopen($this->logoutURL, 'GET', $params);
        }
        return $response;
    }
    
    /**
     * Make an fopen request
     *
     * @return string API results
     */
    protected function fopen($url, $method, $fields = null) {
        $path = $url . "?" . ((is_array($fields)) ? http_build_query($fields) : $fields);
        $stream = ($this->sslVerifyPeer) ? fsockopen($path, 'r') : fopen($path, 'r');
        $response = stream_get_contents($stream);
        return $response;
    }
    /**
     * Make an HTTP request
     *
     * @return string API results
     */
    protected function http($url, $method, $fields = null, $headers = null) {
        if (! function_exists('curl_init'))
            exit('{"success":false,"msg":"install curl"}');
        $ci = curl_init();
        
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
        
        switch ($method) {
            case 'POST' :
                curl_setopt($ci, CURLOPT_POST, true);
                if (! empty($fields))
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $fields);
                break;
            case 'GET' :
                if (! empty($fields))
                    $url = $url . "?" . ((is_array($fields)) ? http_build_query($fields) : $fields);
                break;
        }
        
        if (isset($this->access_token) && $this->access_token)
            $headers[] = "Authorization: OAuth2 " . $this->access_token;
        
        $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
        
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_URL, $url);
        
        $response = curl_exec($ci);
        curl_close($ci);
        
        return $response;
    }
}

// PHP END