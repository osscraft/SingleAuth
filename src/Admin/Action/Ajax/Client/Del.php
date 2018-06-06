<?php

namespace Dcux\Admin\Action\Ajax\Client;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Client;
use Dcux\SSO\Service\ClientService;

class Del extends AjaxPermission
{
    protected $clientService;
    public function onCreate()
    {
        parent::onCreate();
        $this->clientService = ClientService::getInstance();
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
            $ret = $this->clientService->del($id);
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
