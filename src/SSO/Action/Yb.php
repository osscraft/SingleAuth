<?php
namespace Dcux\SSO\Action;

use Autoloader;
use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;
use Lay\Advance\Util\Utility;

use Dcux\SSO\Kernel\UAction;
use Dcux\SSO\Service\IdentifyService;

use YBOpenApi;
use YBLANG;

class Yb extends UAction
{
    private $identifyService;
    public function onCreate()
    {
        parent::onCreate();
        $this->identifyService = IdentifyService::getInstance();
    }
    public function onGet()
    {
        global $CFG;
        require_once App::$_rootpath . "/web/inc/YBApi_sdk_php/classes/yb-globals.inc.php";
        Autoloader::addPath(App::$_rootpath . "/web/inc/YBApi_sdk_php/classes/");

        $CFG['SSO_CLIENT_ID'] = 'f9af68e80401e45b';
        $CFG['SSO_CLIENT_SECRET'] = 'a9d97b03609528370dc503d8c465a017';
        $CFG['SSO_CALLBACK'] = 'http://sso.project.dcux.com/yb.php';
        $CFG['use_verify_me'] = false;
        $CFG['use_test_uid'] = false;
        $CFG['test_uid'] = 'liaiyong';

        // ba2f08ae0fecd26059d82cd1a07d92c7c678f08d

        $api = YBOpenApi::getInstance();
        $api->init($CFG['SSO_CLIENT_ID'], $CFG['SSO_CLIENT_SECRET'], $CFG['SSO_CALLBACK']);
        $au = $api->getAuthorize();

        // 将原地址存入session
        if (empty($_SESSION['__RAWURL__']) && empty($_GET['rawurl'])) {
            exit("无效请求！");
        } elseif (!empty($_GET['rawurl'])) {
            $rawurl = $_SESSION['__RAWURL__'] = $_GET['rawurl'];
        } elseif (!empty($_SESSION['__RAWURL__'])) {
            $rawurl = $_SESSION['__RAWURL__'];
        }
        //
        if (empty($_GET['code'])) {
            // 导向至易班认证页
            $authURL = $au->forwardurl($_GET['url']);
            header("Location: $authURL");
            exit;
        } else {
            // 易班认证回调
            $info = $au->querytoken($_GET['code']);
            if (isset($info['access_token'])) {
                $_SESSION['__TOKEN__'] = $info['access_token'];
                $_SESSION['__EXPIRES__'] = $info['expires'];
            } else {
                exit($info['msgCN']);
            }
        }

        if (empty($_SESSION['__TOKEN__'])) {
            exit(YBLANG::EXIT_NOT_AUTHORIZED);
        }
        // token已经过期
        if ($_SESSION['__EXPIRES__'] < time()) {
            // 导向至易班认证页
            $authURL = $au->forwardurl();
            header("Location: $authURL");
            exit;
        }

        /**
         * 功能接口只需要token值就可以调用
         *
         */
        $api = $api->bind($_SESSION['__TOKEN__']);

        if ($CFG['use_verify_me']) {
            // TODO
        } else {
            $user = $api->getUser();
            $me = $user->me();
            echo "<pre>";
            print_r($me);
            $real = $user->realme();
            print_r($real);
            $verify = $user->verifyme();
            print_r($real);

            if ($verify['status'] == 'success') {
                $uid = $verify['info']['studentid'];
            } elseif (!empty($CFG['use_test_uid'])) {
                $uid = $CFG['test_uid'];
            }
            if (!empty($uid)) {
                $userinfo = $this->identifyService->getUser($uid);
            }
            if (empty($userinfo)) {
                exit("用户不存在！");
            }

            $this->updateSessionUser($userinfo);
            //print_r($userinfo);
            //print_r($rawurl);exit;
            // 跳回原URL
            Utility::redirectPost($rawurl);
            unset($_SESSION['__TOKEN__']);
            unset($_SESSION['__EXPIRES__']);
            unset($_SESSION['__RAWURL__']);
            //header("Location: $rawurl&direct=1");
        }
        exit;
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
