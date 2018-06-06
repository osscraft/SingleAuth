<?php

namespace Dcux\Cli\Action\Oauth2;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\OAuth2CodeService;
use Dcux\SSO\Service\OAuth2TokenService;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatService;

class Clean extends CronAction
{
    protected $oauth2CodeService;
    protected $oauth2TokenService;
    public function onCreate()
    {
        parent::onCreate();
        $this->oauth2CodeService = OAuth2CodeService::getInstance();
        $this->oauth2TokenService = OAuth2TokenService::getInstance();
    }
    public function on()
    {
        // total 60s, per 5s
        for ($i=0; $i < 12; $i++) {
            $retCode = $this->oauth2CodeService->clean();
            $retToken = $this->oauth2TokenService->clean();
            sleep(5);
        }
        //$clients = StatService::getInstance()->getStatBrowserDistribution();
        //$this->template->push("clients", $clients);
        if ($retCode && $retToken) {
            $this->template->push("code", 0);
            $this->template->push("data", "cleaned code and token");
        } elseif ($retCode) {
            $this->template->push("code", 900101);
            $this->template->push("msg", "not cleaned token");
        } elseif ($retToken) {
            $this->template->push("code", 900102);
            $this->template->push("msg", "not cleaned code");
        } else {
            $this->template->push("code", 900103);
            $this->template->push("msg", "not cleaned code and token");
        }
        //$this->template->push('code', 0);
    }
}
