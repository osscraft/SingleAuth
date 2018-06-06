<?php

namespace Dcux\Admin\Action\Ajax\Skin;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Skin;
use Dcux\SSO\Service\SettingService;

class Set extends AjaxPermission
{
    protected $settingService;
    public function onCreate()
    {
        $this->settingService = SettingService::getInstance();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $key = empty($_REQUEST['key']) ? 'main' : $_REQUEST['key'];
        if ($key == 'main') {
            $this->setTheme($key);
        } elseif ($key == 'admin') {
            $this->setTheme($key);
        }
    }
    protected function setTheme($sign)
    {
        $info = array();
        $info['k'] = $k = "theme.$sign";
        $info['v'] = $v = empty($_REQUEST['v']) ? '' : $_REQUEST['v'];

        $ret = $this->settingService->replace($info);
        if (empty($ret)) {
            $this->template->push('code', 500003);
            $this->template->push('error', 'update failure');
        } else {
            $this->template->push('data', 'success');
        }
    }
}
// PHP END
