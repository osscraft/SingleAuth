<?php
namespace Dcux\Admin\Action;

use Autoloader;
use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Admin\Kernel\AAction;
use Dcux\Admin\Kernel\MenuAction;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Service\UserService;
//use Dcux\SSO\Manager\UserManager;
use Dcux\SSO\Manager\LogUserManager;
use Dcux\SSO\Manager\ClientManager;
use Dcux\SSO\Core\Util;
use Dcux\SSO\SDK\SSOToOAuth2;
use Dcux\SSO\SDK\SSOClient;

class Index extends MenuAction {
    protected $userService;
    public function cmd() {
        return 'index';
    }
    public function onCreate() {
        parent::onCreate();
        $this->userService = UserService::getInstance();
    }
    public function onGet() {
        global $CFG;
        $out = array ();
        $oauth = new SSOToOAuth2($CFG['SSO_CLIENT_ID'], $CFG['SSO_CLIENT_SECRET']);

        $out['URL'] = $oauth->getAuthorizeURL($CFG['SSO_CALLBACK']);
		
        if (isset($_REQUEST['code'])) {
            $keys = array ();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $CFG['SSO_CALLBACK'];
            $ret = $oauth->getAccessToken('code', $keys);
            if ($ret && !empty($token['error'])) {
                $error = $token['error'];
            } else if($ret) {
                $token = $_SESSION['token'] = $ret;
            }
        } else if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
        }
        // redirect if empty code
        if(!empty($token) && isset($_REQUEST['code'])) {
            /*CLOSE THE SESSION WITH USER DATA*/
            //session_write_close();
            /*AND STARTING A NEW SESSION*/
            //session_start();
            $this->template->redirect('index.php');
        } else if(! empty($token) && empty($_SESSION['user'])) {
            $_SESSION['token'] = $token;
            // new SSOClient instance
            $oauth->access_token = empty($token['access_token']) ? '' : $token['access_token'];
            $oauth->refresh_token = empty($token['refresh_token']) ? '' : $token['refresh_token'];
            $client = new SSOClient($CFG['SSO_CLIENT_ID'], $CFG['SSO_CLIENT_SECRET'], empty($token['access_token']) ? '' : $token['access_token'], empty($token['refresh_token']) ? '' : $token['refresh_token']);
			//$count = UserService::counts(array('uid'=> array('0', '>')));
            $root = $this->userService->get($CFG['root_uid']);
			if(empty($root)) {
                $_SESSION['user'] = $root = array (
                        "uid" => $CFG['root_uid'],
                        "username" => $CFG['root_username'],
                        "isAdmin" => '2'
                );
                $this->userService->add($root);
                //去创建超级管理员
                $this->template->redirect('user.php?key=tocreate', array(), false);
            } else {
                $ldapuser = $client->getUserInfo();
                // $_user = UserManager::read(array('username'=>$uid));
                if (!empty($ldapuser) && empty($ldapuser['error'])) {
                    $uid = !empty($ldapuser['uid']) ? $ldapuser['uid'] : $ldapuser['cn'];
                    $_user = UserService::read(array('uid'=>$uid));
                    if (empty($_user)) {
                        $out['SIGN'] = 'invalid_user';
                        $error = $out['REQUEST_ERROR'] = $CFG['LANG']['DENIED_USER'] . "，" . $CFG['LANG']['PLEASE_LOGOUT'];
                    } else if(!empty($_user) && $_user['isAdmin'] < 1) {
                        $out['SIGN'] = 'invalid_user';
                        $error = $out['REQUEST_ERROR'] = $CFG['LANG']['DENIED_USER'] . "，" . $CFG['LANG']['PLEASE_LOGOUT'];
                    } else {
                        $_SESSION['user'] = $_user;
                    }
                } else if(!empty($ldapuser)) {
                    // invalid token
                    if ($ldapuser['error'] == 'invalid_access_token') {
                        $out['SIGN'] = 'invalid_access_token';
                        $error = $out['REQUEST_ERROR'] = $ldapuser['error'];
                    } else {
                        $out['SIGN'] = 'unkown_error';
                        $error = $out['REQUEST_ERROR'] = $ldapuser['error'];
                    }
                } else {
                    // request error
                    $out['SIGN'] = 'unkown_error';
                    $error = $out['REQUEST_ERROR'] = 'unkown_error';
                }
            }
        }

        
        if (empty($error)) {
            $out['TITLE'] = $CFG['LANG']['TITLE_INDEX'];
            $out['WELCOME'] = $CFG['LANG']['WELCOME'];
            $out['LANG'] = $CFG['LANG'];
            $out['SIGN'] = 'index';
            $out['user'] = empty($_SESSION['user']) ? [] : $_SESSION['user'];
            $out['SESSION'] = $_SESSION;
            $this->template->push($out);
            $this->template->file('index.php');
        } else {
            $this->template->push($out);
            $this->template->file('error.php');
        }
    }
    public function onPost() {
        $this->onGet();
    }
    protected function userDenied() {
        $this->template->push(array(
                'SIGN' => 'invalid_access_token',
                'REQUEST_ERROR' => $CFG['LANG']['DENIED_USER'] . "，" . $CFG['LANG']['PLEASE_LOGOUT'] 
        ));
    }
    protected function invalidAccessToken($err) {
        $this->template->push(array(
                'SIGN' => 'invalid_token',
                'REQUEST_ERROR' => $err
        ));
    }
    protected function errorResponse($error, $error_description = null, $error_uri = null) {
        $this->template->push(array(
                'SIGN' => $error,
                'REQUEST_ERROR' => empty($error_description) ? $error : $error_description
        ));
    }
}
// PHP END