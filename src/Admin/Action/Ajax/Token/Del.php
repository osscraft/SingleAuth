<?php

namespace Dcux\Admin\Action\Ajax\Token;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Token;
use Dcux\SSO\Service\OAuth2TokenService;

class Del extends AjaxPermission
{
    protected $oauth2TokenService;
    public function onCreate()
    {
        parent::onCreate();
        $this->oauth2TokenService = OAuth2TokenService::getInstance();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $id = empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];

        if (empty($id)) {
            $this->template->push('code', 501001);
            $this->template->push('error', 'invalid id');
        } else {
            $ret = $this->oauth2TokenService->del($id);
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
