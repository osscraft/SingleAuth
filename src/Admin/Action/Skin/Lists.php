<?php

namespace Dcux\Admin\Action\Skin;

use Dcux\Admin\Kernel\MenuPermission;
use Dcux\Admin\Action\Skin;
use Dcux\SSO\Service\SettingService;

class Lists extends MenuPermission
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
        if (empty($t) || $t == 'default') {
            //直接跳至
            $this->template->redirect('/admin/skin.php', array(), false);
        }
        $this->settingService = SettingService::getInstance();
    }
    public function onGet()
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
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
