<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;

use Dcux\SSO\Kernel\UAction;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\ScopeService;
use Dcux\SSO\Service\OAuth2CodeService;
use Dcux\SSO\Service\OAuth2TokenService;
use Dcux\SSO\Service\IdentifyService;

class Resource extends UAction
{
    protected $clientService;
    protected $scopeService;
    protected $oauth2CodeService;
    protected $oauth2TokenService;
    protected $identify;
    public function onCreate()
    {
        $this->clientService = ClientService::getInstance();
        $this->scopeService = ScopeService::getInstance();
        $this->oauth2CodeService = OAuth2CodeService::getInstance();
        $this->oauth2TokenService = OAuth2TokenService::getInstance();
        $this->identify = IdentifyService::getInstance();
        parent::onCreate();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $token = empty($_REQUEST['access_token']) ? '' : $_REQUEST['access_token'];
        // $userid = empty($_REQUEST['userid']) ? : $_REQUEST['userid'];
        $oauth2token = $this->oauth2TokenService->get($token);
        if (! empty($oauth2token) && $oauth2token['type'] == OAuth2::TOKEN_TYPE_ACCESS) { // && $userid == $oauth2token['userid']
            list($scopeStr, $scopeArr) = $this->scopeService->filter($oauth2token['scope']);
            /*
             * $splits = explode(',', $scopeStr);
             * if(in_array('info', $splits) || in_array(1000, $splits)) {
             */
            // $user = $this->userService->get($oauth2token['userid']);
            $user = $this->identify->getUser($oauth2token['username'], $scopeStr);
            if ($user) {
                $this->template->push($user);
                $params = $this->genInfo($oauth2token, $user);
                $this->template->push($params);
            //$this->template->header('Content-Type: text/xml');
            } else {
                $this->errorResponse('invalid_user');
            }
            /*
             * } else {
             * $this->errorResponse('unsupported_scope');
             * }
             */
        } else {
            $this->errorResponse('invalid_token');
        }
        /*
         * $oauth2 = OAuth2::getInstance();
         * $oauth2->verifyAccessToken($_REQUEST);
         */
    }
    protected function genInfo($oauth2token, $user)
    {
        return array();
    }
}
// PHP END
