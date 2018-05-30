<?php

namespace Dcux\SSO\OAuth2;

use Dcux\SSO\Manager\ClientManager;
use Dcux\SSO\Manager\TokenManager;
use Dcux\SSO\Manager\AuthCodeManager;
use Dcux\SSO\Manager\LogUserManager;
use Dcux\SSO\Manager\LogClientManager;
use Dcux\SSO\Resource\ResourceOfLdap;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Log\LogContainer;
use Dcux\SSO\Model\User;

/**
 * OAuth2模块
 * 
 * @category oauth
 * @package classes
 * @subpackage oauth
 * @author liaiyong <liaiyong@dcux.com>
 * @version 1.0
 * @copyright 2005-2012 dcux Inc.
 * @link http://www.dcux.com
 *      
 */
class OAuth2 {
    
    /**
     *
     * 对象实例，单键模式。
     * 
     * @var \OAuth2
     */
    private static $instance = NULL;
    /**
     * 构造方法
     */
    private function __construct() {
    }
    
    /**
     *
     * 将对象实例化
     *
     * @author liangjun@dcux.com
     * @return object
     */
    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new OAuth2();
        }
        return self::$instance;
    }
    const REQUEST_TYPE_CODE = 'code';
    const REQUEST_TYPE_POST = 'post'; // 用户提交登录
    const REQUEST_TYPE_TOKEN = 'token';
    const REQUEST_TYPE_PASSWORD = 'password';
    const REQUEST_TYPE_REFRESH_TOKEN = 'refresh_token';
    const REQUEST_TYPE_SHOW = 'show';
    const RESPONSE_TYPE_CODE = 'code';
    const RESPONSE_TYPE_TOKEN = 'token';
    const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';
    const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';
    const CLIENT_TYPE_WEB = 1;
    const CLIENT_TYPE_DESKTOP = 2;
    const CLIENT_TYPE_IMPLICIT = 3;
    const CLIENT_TYPE_MOBILE = 4;
    const TOKEN_TYPE_ACCESS = 0;
    const TOKEN_TYPE_REFRESH = 1;
    const HTTP_QUERY_PARAM_REQUEST_TYPE = 'request_type';
    const HTTP_QUERY_PARAM_RESPONSE_TYPE = 'response_type';
    const HTTP_QUERY_PARAM_GRANT_TYPE = 'grant_type';
    const HTTP_QUERY_PARAM_CLIENT_ID = 'client_id';
    const HTTP_QUERY_PARAM_REDIRECT_URI = 'redirect_uri';
    const HTTP_QUERY_PARAM_CLIENT_SECRET = 'client_secret';
    const HTTP_QUERY_PARAM_CODE = 'code';
    const HTTP_QUERY_PARAM_TOKEN = 'token';
    const HTTP_QUERY_PARAM_USERNAME = 'username';
    const HTTP_QUERY_PARAM_PASSWORD = 'password';
    const HTTP_QUERY_PARAM_ACCESS_TOKEN = 'token';
    const HTTP_QUERY_PARAM_ACCESS_TOKEN_EXPIRES = 'expires_in';
    const HTTP_QUERY_PARAM_REFRESH_TOKEN = 'refresh_token';
    const HTTP_QUERY_PARAM_USER_ID = 'user_id';
    const HTTP_QUERY_PARAM_VERIFY_CODE = 'verifyCode';
    const HTTP_QUERY_PARAM_SCOPE = 'scope';
    const HTTP_QUERY_PARAM_DISPLAY = 'display';
    const HTTP_QUERY_PARAM_STATE = 'state';
    public static function getReferer($query = false) {
        if(empty($query)) {
            $referer = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
        } else {
            $referer = empty($_REQUEST['referer']) ? (empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER']) : $_REQUEST['referer'];
        }
        return $referer;
    }
    /**
     *
     * 生成一个code,此code为唯一值。可以是：授权码、访问令牌、刷新令牌
     *
     * @return string
     */
    public static function generateCode() {
        return md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
    }
    public static function getResponseType() {
        $responseType = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_RESPONSE_TYPE];
        if (in_array($responseType, array (
                OAuth2::RESPONSE_TYPE_CODE,
                OAuth2::RESPONSE_TYPE_TOKEN 
        ))) {
            return $responseType;
        } else {
            return OAuth2::RESPONSE_TYPE_CODE;
        }
    }
    public static function getRedirectURI() {
        $redirectURI = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_REDIRECT_URI];
        if (empty($redirectURI)) {
            return '';
        } else {
            return $redirectURI;
        }
    }
    public static function getClientId() {
        $clientId = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_CLIENT_ID];
        if (empty($clientId)) {
            return '';
        } else {
            return $clientId;
        }
    }
    public static function getClientSecret() {
        $clientSecret = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_CLIENT_SECRET];
        if (empty($clientSecret)) {
            return '';
        } else {
            return $clientSecret;
        }
    }
    public static function getRequestScope() {
        $scope = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_SCOPE];
        if (empty($scope)) {
            return '';
        } else {
            return $scope;
        }
    }
    /**
     *
     * @param HttpRequest $request            
     * @param HttpResponse $response            
     * @return array
     */
    public static function getSessionUser() {
        $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : '';
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
        if (empty($uid)) {
            return false;
        } else {
            $user = array ();
            $user['uid'] = $uid;
            $user['username'] = $username;
            $user['role'] = $role;
            return $user;
        }
    }
    public static function getRequestType() {
        if ($_REQUEST[OAuth2::HTTP_QUERY_PARAM_GRANT_TYPE]) {
            $grantType = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_GRANT_TYPE];
        } else {
            $grantType = OAuth2::GRANT_TYPE_AUTHORIZATION_CODE;
        }
        switch ($grantType) {
            case OAuth2::GRANT_TYPE_AUTHORIZATION_CODE :
                $requestType = OAuth2::REQUEST_TYPE_TOKEN;
                break;
            case OAuth2::GRANT_TYPE_PASSWORD :
                $requestType = OAuth2::REQUEST_TYPE_PASSWORD;
                break;
            case OAuth2::GRANT_TYPE_REFRESH_TOKEN :
                $requestType = OAuth2::REQUEST_TYPE_REFRESH_TOKEN;
                break;
            default :
                $grantType = OAuth2::GRANT_TYPE_AUTHORIZATION_CODE;
                $requestType = OAuth2::REQUEST_TYPE_TOKEN;
                break;
        }
        return $requestType;
    }
    public static function checkRequest($requestType = '') {
        if (empty($requestType)) {
            $requestType = OAuth2::REQUEST_TYPE_CODE;
        }
        $responseType = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_RESPONSE_TYPE];
        $grantType = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_GRANT_TYPE];
        $clientId = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_CLIENT_ID];
        $redirectURI = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_REDIRECT_URI];
        $clientSecret = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_CLIENT_SECRET];
        $code = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_CODE];
        $token = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_TOKEN];
        $username = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_USERNAME];
        $password = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_PASSWORD];
        $refreshToken = $_REQUEST[OAuth2::HTTP_QUERY_PARAM_REFRESH_TOKEN];
        $uid = empty($_REQUEST[OAuth2::HTTP_QUERY_PARAM_USER_ID]) ?  : $_REQUEST[OAuth2::HTTP_QUERY_PARAM_USER_ID];
        $id = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
        $name = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        $ret = true;
        
        switch ($requestType) {
            case OAuth2::REQUEST_TYPE_CODE :
                if (empty($clientId) || empty($redirectURI)) {
                    $ret = false;
                } else if ($responseType && $responseType != OAuth2::RESPONSE_TYPE_CODE && $responseType != OAuth2::RESPONSE_TYPE_TOKEN) {
                    $ret = false;
                }
                break;
            case OAuth2::REQUEST_TYPE_POST :
                // 登录会话用户没有存在session里
                if (empty($clientId) || empty($redirectURI) || ((empty($username) || empty($password)) && (empty($id) || empty($name)))) {
                    $ret = false;
                } else if ($responseType && $responseType != OAuth2::RESPONSE_TYPE_CODE && $responseType != OAuth2::RESPONSE_TYPE_TOKEN) {
                    $ret = false;
                }
                break;
            case OAuth2::REQUEST_TYPE_TOKEN :
                if (empty($clientId) || empty($redirectURI) || empty($clientSecret) || empty($code)) {
                    $ret = false;
                }
                break;
            case OAuth2::REQUEST_TYPE_PASSWORD :
                if (empty($clientId) || empty($clientSecret) || empty($grantType) || empty($username) || empty($password)) {
                    $ret = false;
                }
                break;
            case OAuth2::REQUEST_TYPE_REFRESH_TOKEN :
                if (empty($clientId) || empty($clientSecret) || empty($grantType) || empty($refreshToken)) {
                    $ret = false;
                }
                break;
            case OAuth2::REQUEST_TYPE_SHOW :
                if (empty($token) || empty($uid)) {
                    $ret = false;
                }
                break;
            default :
                $ret = false;
                break;
        }
        return $ret;
    }
}
?>
