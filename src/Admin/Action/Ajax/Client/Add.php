<?php

namespace Dcux\Admin\Action\Ajax\Client;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\Client;
use Dcux\SSO\Service\ClientService;

class Add extends AjaxPermission
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
        $info = array();
        $info['clientId'] = $clientId = empty($_REQUEST['clientId']) ? '' : $_REQUEST['clientId'];
        $info['clientName'] = $clientName = empty($_REQUEST['clientName']) ? '' : $_REQUEST['clientName'];
        $info['clientSecret'] = $clientSecret = empty($_REQUEST['clientSecret']) ? '' : $_REQUEST['clientSecret'];
        $info['clientType'] = empty($_REQUEST['clientType']) ? 'webApp' : $this->clientService->deparseType($_REQUEST['clientType']);
        $info['redirectURI'] = empty($_REQUEST['redirectURI']) ? '' : $_REQUEST['redirectURI'];
        $info['clientDescribe'] = empty($_REQUEST['clientDescribe']) ? '' : $_REQUEST['clientDescribe'];
        $info['clientScope'] = empty($_REQUEST['clientScope']) ? '' : $_REQUEST['clientScope'];
        $info['clientLocation'] = empty($_REQUEST['clientLocation']) ? '' : $_REQUEST['clientLocation'];
        $info['clientLogoUri'] = empty($_REQUEST['clientLogoUri']) ? '' : $_REQUEST['clientLogoUri'];
        $info['clientIsShow'] = empty($_REQUEST['clientIsShow']) ? '' : $_REQUEST['clientIsShow'];
        $info['clientVisible'] = empty($_REQUEST['clientVisible']) ? '' : $_REQUEST['clientVisible'];
        $info['tokenLifetime'] = empty($_REQUEST['tokenLifetime']) ? '' : $_REQUEST['tokenLifetime'];

        if (empty($clientId)) {
            $this->template->push('code', 501002);
            $this->template->push('error', 'invalid client id');
        } elseif (empty($clientName)) {
            $this->template->push('code', 501003);
            $this->template->push('error', 'invalid client name');
        } elseif (empty($clientSecret)) {
            $this->template->push('code', 501004);
            $this->template->push('error', 'invalid client secret');
        } else {
            $ret = $this->clientService->add($info);
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
