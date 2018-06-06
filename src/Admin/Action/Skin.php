<?php
namespace Dcux\Admin\Action;

use Lay\Advance\Core\Preloading;
use Lay\Advance\Core\Configuration;

use Dcux\Admin\Kernel\AAction;
use Dcux\Admin\Kernel\MenuPermission;
use Dcux\SSO\Service\SettingService;

class Skin extends MenuPermission
{
    protected $settingService;
    public function cmd()
    {
        return 'skin';
    }
    public function onCreate()
    {
        parent::onCreate();
        $t = $this->template->getTheme();
        if (!empty($t) && $t != 'default') {//different theme different php
            //直接跳至
            $this->template->redirect('/admin/skin/lists.php', array(), false);
        }
        if (empty($_SESSION['token']) || empty($_SESSION['user']) || $_SESSION['user']['isAdmin'] < 1) {
            $this->template->redirect('index.php', array(), false);
        }
        $this->settingService = SettingService::getInstance();
    }
    public function onGet()
    {
        global $CFG;
        $out = array();
        $st =time() + microtime();
        $_REQUEST['key'] = empty($_REQUEST['key']) ? 'list' : $_REQUEST['key'];
        $out['TITLE'] = $CFG['LANG']['SETTING_MANAGER'];
        $out['SESSION'] = $_SESSION;
        switch ($_REQUEST['key']) {
            case 'modify':
                $this->doModify();
                break;
            case 'list':
            default:
                $this->doList();
                break;
        }
        
        $et = time() + microtime();
        $out['SIGN'] = 'skin';
        $out['LANG'] = $CFG['LANG'];
        $out['TIME'] = array(
                'START_TIME' => $st,
                'END_TIME' => $et,
                'DIFF_TIME' => ($et - $st)
        );
        $out['RAND'] = rand(100000, 999999);
        $this->template->push($out);
    }
    public function onPost()
    {
        $this->onGet();
    }
    public function isTheme($k)
    {
        return !(strpos($k, 'theme')===false);
    }

    protected function setTheme($sign, $value)
    {
        $info = array();
        $info['k'] = $k = "theme.$sign";
        $info['v'] = $v = empty($value) ? '' : $value;

        return $this->settingService->replace($info);
    }
    protected function doModify()
    {
        global $CFG;
        $out = array();
        $info = array();
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFING'];
        if (!empty($_REQUEST['skin-main'])) {
            $key = "main";
            $out['SUCCESS'] = $this->setTheme($key, $_REQUEST['skin-main']);
        } elseif (!empty($_REQUEST['skin-admin'])) {
            $key = "admin";
            $out['SUCCESS'] = $this->setTheme($key, $_REQUEST['skin-admin']);
        }
        $this->template->file('skin/editok.php');
        $this->template->push($out);
    }
    protected function doList()
    {
        global $CFG;
        $mSetting = $this->settingService->get('theme.main');
        $aSetting = $this->settingService->get('theme.admin');
        $main = empty($mSetting) ? empty($CFG['theme']['main']) ? 'default' : $CFG['theme']['main'] : $mSetting['v'];
        $admin = empty($aSetting) ? empty($CFG['theme']['admin']) ? 'default' : $CFG['theme']['admin'] : $aSetting['v'];
        $mains = empty($CFG['themes']['main']) ? array('default') : $CFG['themes']['main'];
        $admins = empty($CFG['themes']['admin']) ? array('default') : $CFG['themes']['admin'];
        $this->template->push('main', $main);
        $this->template->push('mains', $mains);
        $this->template->push('admin', $admin);
        $this->template->push('admins', $admins);
        $this->template->file('skin/list.php');
    }
}
