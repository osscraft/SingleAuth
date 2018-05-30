<?php
namespace Dcux\ChangePass\Action;

use Lay\Advance\Core\Errode;

use Dcux\ChangePass\Kernel\App;
use Dcux\ChangePass\Kernel\AAction;
use Dcux\SSO\Service\MysqlUserService;
use Dcux\SSO\SDK\SSOToOAuth2;
use Dcux\SSO\SDK\SSOClient;

class Index extends AAction {
    protected $mysqlUserService;
    public function onCreate() {
        parent::onCreate();
        $this->mysqlUserService = MysqlUserService::getInstance();
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
			//$this->template->push($ret);
            if ($ret && $token['error']) {
                $error = $token['error'];
				//$this->template->push($error);
            } else if($ret) {
                $token = $_SESSION['cp_token'] = $ret;
				//$this->template->push($token);
            }
        } else if(!empty($_SESSION['cp_token'])) {
            $token = $_SESSION['cp_token'];
			//$this->template->push($token);
        }
		
		 if(!empty($token) && isset($_REQUEST['code'])) {
            $this->template->redirect('index.php');
        } else if(! empty($token) && empty($_SESSION['cp_user'])) {
            $_SESSION['cp_token'] = $token;
            // new SSOClient instance
            $oauth->access_token = $token['access_token'];
            $oauth->refresh_token = $token['refresh_token'];
            $client = new SSOClient($CFG['SSO_CLIENT_ID'], $CFG['SSO_CLIENT_SECRET'], $token['access_token'], $token['refresh_token']);
			//$count = UserService::counts(array('uid'=> array('0', '>')));
			$ldapuser = $client->getUserInfo();
			// $_user = UserManager::read(array('username'=>$uid));
			if (!empty($ldapuser) && empty($ldapuser['error'])) {
				//$uid = !empty($ldapuser['uid']) ? $ldapuser['uid'] : $ldapuser['cn'];
				//$_user = $this->mysqlUserService->get(array('uid'=>$uid));
                $_user = $ldapuser;
				if (empty($_user)) {
					$error = $out['REQUEST_ERROR'] = $CFG['LANG']['DENIED_USER'] . "£¬" . $CFG['LANG']['PLEASE_LOGOUT'];
				} else {
					$_SESSION['cp_user'] = $_user;
				}
			} else if(!empty($ldapuser)) {
				// invalid token
				if ($ldapuser['error'] == 'invalid_access_token') {
					$error = $out['REQUEST_ERROR'] = $ldapuser['error'];
				} else {
					$error = $out['REQUEST_ERROR'] = $ldapuser['error'];
				}
			} else {
				// request error
				$error = $out['REQUEST_ERROR'] = 'unkown_error';
			}
        }

        
        if (empty($error)) {
            $out['TITLE'] = $CFG['LANG']['TITLE_INDEX'];
            $out['WELCOME'] = $CFG['LANG']['WELCOME'];
            $out['user'] = $_SESSION['cp_user'];
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
}
// PHP END