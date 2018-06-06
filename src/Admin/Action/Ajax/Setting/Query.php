<?php

namespace Dcux\Admin\Action\Ajax\Setting;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Setting;
use Dcux\SSO\Service\SettingService;

class Query extends AjaxPermission
{
    protected $settingService;
    public function onCreate()
    {
        $this->settingService = SettingService::getInstance();
    }
    public function onGet()
    {
        $settings = $this->settingService->getSettingListAll();
        $data = array();
        foreach ($settings as $setting) {
            if (strpos($setting['k'], 'theme')===false) {
                $data[]=$setting;
            } else {
            }
        }
        $this->template->push('data', $data);
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
