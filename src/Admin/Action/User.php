<?php
namespace Dcux\Admin\Action;

use Dcux\Admin\Kernel\AAction;
use Dcux\Admin\Kernel\MenuPermission;
use Dcux\SSO\Service\UserService;
use Dcux\SSO\Manager\UserManager;
use Dcux\SSO\SDK\SSOClient;

class User extends MenuPermission {
    public function cmd() {
        return 'user';
    }
    public function onCreate() {
        parent::onCreate();
        $t = $this->template->getTheme();
        if(!empty($t) && $t != 'default') {//different theme different php
            //直接跳至
            $this->template->redirect('/admin/user/lists.php', array(), false);
        }
        if (empty($_SESSION['token']) || empty($_SESSION['user']) || $_SESSION['user']['isAdmin'] < 2) {
            $this->template->redirect('index.php', array(), false);
        }
    }
    public function onGet() {
        global $CFG;
        $out = array ();
        $st = time() + microtime();
        $_REQUEST['key'] = empty($_REQUEST['key']) ? 'list' : $_REQUEST['key'];
        if (! empty($_SESSION['user']) && $_SESSION['user']['uid'] === $CFG['root_uid']) {
            $_REQUEST['init'] = 1;
        } else {
            $_REQUEST['init'] = 0;
        }
	
        $out['TITLE'] = $CFG['LANG']['USER_MANAGER'];
        $out['SESSION'] = $_SESSION;
        $out['LEFTER'] = true;
        switch ($_REQUEST['key']) {
            case 'view' :
                $this->doView();
                break;
            case 'tocreate' :
                $this->doToCreate();
                break;
            case 'create' :
                $this->doCreate();
                break;
            case 'tomodify' :
                $this->doToModify();
                break;
            case 'modify' :
                $this->doModify();
                break;
            case 'todelete' :
                $this->doToDelete();
                break;
            case 'delete' :
                $this->doDelete();
                break;
            case 'list' :
            default :
				$this->doList();
                break;
        }
        
        $et = time() + microtime();
        $out['SIGN'] = 'user';
        $out['LANG'] = $CFG['LANG'];
        $out['TIME'] = array (
                'START_TIME' => $st,
                'END_TIME' => $et,
                'DIFF_TIME' => ($et - $st) 
        );
        $out['RAND'] = rand(100000, 999999);
        $this->template->push($out);
    }
    public function onPost() {
        $this->onGet();
    }

    protected function doView() {
        global $CFG;
        $out = array();
        $out['view'] = true;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
        //$out['USER'] = UserManager::read();
        $uid = $_REQUEST['uid'];
        $out['USER']=UserService::read(array('uid'=>$uid));
        //$out['use']=UserManager::read(array('uid'=>$uid));
        $this->template->push($out);
        $this->template->file('user/view.php');
    }
    protected function doToCreate() {
        global $CFG;
        $out = array();
        $out['tocreate'] = true;
        $out['LEFTER'] = false;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['CREATE'];
        if ($_REQUEST['init']) {
            $token = $_SESSION['token'];
            $client = new SSOClient($CFG['SSO_CLIENT_ID'], $CFG['SSO_CLIENT_SECRET'], $token['access_token'], $token['refresh_token']);
            $ldapuser = $client->getUserInfo();
            // $_token = TokenManager::read(array('oauthToken'=>$_SESSION['token']['access_token']));echo "<pre>";print_r($_SESSION);print_r($_token);
            // $_SESSION['user'] = array("username"=>$_token['username'],"isAdmin"=>'1');print_r($_SESSION);print_r($ldapuser);exit;
            $uid = ($ldapuser['uid']) ? $ldapuser['uid'] : $ldapuser['cn'];
            $username = ($ldapuser['username']) ? $ldapuser['username'] : $ldapuser['sn'];
            $_SESSION['user'] = array (
                    "uid" => $uid,
                    "username" => $username,
                    "isAdmin" => '2' 
            );
            // MemSession::setSession();
            $out['USER'] = $_SESSION['user'];
            $out['SESSION'] = $_SESSION;
        }
        $this->template->push($out);
        $this->template->file('user/create.php');
    }
    protected function doCreate() {
        global $CFG;
        $out = array();
        $out['create'] = true;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['CREATING'];
        //var_dump($_REQUEST);
        //exit;
        $out['SUCCESS']=UserService::create($_REQUEST);
        //$out['SUCCESS'] = UserManager::create();
        $this->template->push($out);
        $this->template->file('user/createok.php');
    }
    protected function doToModify() {
        global $CFG;
        $out = array();
        $out['tomodify'] = true;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFY'];
        $uid = $_REQUEST['uid'];
        $out['USER'] = UserService::read(array('uid'=>$uid));
        $this->template->push($out);
        $this->template->file('user/edit.php');
    }
    protected function doModify() {
        global $CFG;
        $out = array();
        $out['modify'] = true;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFING'];
        $out['SUCCESS']=UserService::update($_REQUEST);
        //$out['SUCCESS'] = UserManager::update();
        $this->template->push($out);
        $this->template->file('user/editok.php');
    }
    protected function doToDelete() {
        global $CFG;
        $out = array();
        $out['todelete'] = true;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['DELETE'];
        $uid=$_REQUEST['uid'];
        //$out['USER'] = UserManager::read();
        $out['USER'] = UserService::read(array('uid'=>$uid));
        $this->template->push($out);
        $this->template->file('user/remove.php');
    }
    protected function doDelete() {
        global $CFG;
        $out = array();
        $out['delete'] = true;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['DELETING'];
        $out['SUCCESS']=UserService::delete(array('uid'=>$_REQUEST['uid']));
        //$out['SUCCESS'] = UserManager::delete();
        $this->template->push($out);
        $this->template->file('user/removeok.php');
    }
    protected function doList() {
        global $CFG;
        $out = array();
        $paging = UserService::readUserPaging();
        $total = UserService::readUserTotal($paging);
        $paging->count = $total;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['LIST'];
        $out['USERS'] = UserService::readUserList($paging);
        $out['PAGING'] = $paging->toPaging();
        for($i = 0; $i < count($out['PAGING']['pages']); $i ++) {
            $out['PAGING']['pages'][$i]['url'] = 'user.php?page=' . $out['PAGING']['pages'][$i]['p'] . "&pageSize=" . $out['PAGING']['pages'][$i]['pageSize'];
        }
        $this->template->push($out);
        $this->template->file('user/list.php');
    }
}
// PHP END