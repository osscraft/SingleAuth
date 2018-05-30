<?php

namespace Dcux\Admin\Action\Ajax\Setting;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Setting;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Service\SettingService;

class Upd extends AjaxPermission {
	protected $settingService;
	public function onCreate() {
		$this->settingService = SettingService::getInstance();
	}
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        $k = empty($_REQUEST['k']) ? '' : $_REQUEST['k'];
        $info = array();
        $info['v'] = $v = empty($_REQUEST['v']) ? '' : $_REQUEST['v'];
        $info['info'] = $infos = empty($_REQUEST['info']) ? '' : $_REQUEST['info'];
        if(empty($k)) {
            $this->template->push('code', 501001);
            $this->template->push('error', 'invalid k');
        //} else if(empty($v)) {
        //    $this->template->push('code', 501002);
        //    $this->template->push('error', 'invalid setting v');
        //} else if(empty($infos)) {
        //    $this->template->push('code', 501003);
        //    $this->template->push('error', 'invalid setting info');
        } else {
            $ret = $this->settingService->upd($k, $info);
            if(empty($ret)) {
                $this->template->push('code', 500003);
                $this->template->push('error', 'update failure');
            } else {
                $this->template->push('data', 'success');
            }
        }
    }

}
// PHP END