<?php

namespace Dcux\Admin\Action\Ajax\Skin;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Skin;
use Dcux\SSO\Service\SettingService;

class Del extends AjaxPermission
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
        $k = empty($_REQUEST['k']) ? 0 : $_REQUEST['k'];

        if (empty($k)) {
            $this->template->push('code', 501001);
            $this->template->push('error', 'invalid k');
        } else {
            $ret = $this->settingService->del($k);
            if (empty($ret)) {
                $this->template->push('code', 50002);
                $this->template->push('error', 'delete failure');
            } else {
                $this->template->push('data', 'success');
            }
        }
    }
}
// PHP END
