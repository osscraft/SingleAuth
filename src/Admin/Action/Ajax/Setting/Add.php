<?php

namespace Dcux\Admin\Action\Ajax\Setting;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Setting;
use Dcux\SSO\Service\SettingService;

class Add extends AjaxPermission
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
        $info = array();
        $info['k'] = $k = empty($_REQUEST['k']) ? '' : $_REQUEST['k'];
        $info['v'] = $settingName = empty($_REQUEST['v']) ? '' : $_REQUEST['v'];
        $info['info'] = $settingSecret = empty($_REQUEST['info']) ? '' : $_REQUEST['info'];

        if (empty($k)) {
            $this->template->push('code', 501001);
            $this->template->push('error', 'invalid setting k');
        //} else if(empty($settingName)) {
        //    $this->template->push('code', 501002);
        //    $this->template->push('error', 'invalid setting v');
        //} else if(empty($settingSecret)) {
        //    $this->template->push('code', 501003);
        //    $this->template->push('error', 'invalid setting info');
        } else {
            $ret = $this->settingService->add($info);
            if (empty($ret)) {
                $this->template->push('code', 500001);
                $this->template->push('error', 'add failure');
            } else {
                $this->template->push('data', 'success');
            }
        }
    }
}
// PHP END
