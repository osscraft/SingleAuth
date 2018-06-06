<?php

namespace Dcux\Admin\Action\Ajax\Client;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Client;
use Dcux\SSO\Service\ClientService;

class Query extends AjaxPermission
{
    protected $clientService;
    public function onCreate()
    {
        parent::onCreate();
        $this->clientService = ClientService::getInstance();
    }
    public function onGet()
    {
        $clients = $this->clientService->getClientListAll();
        $this->template->push('data', $clients);
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
