<?php

namespace Dcux\Api\Action\User;

use Lay\Advance\Core\Error;
use Lay\Advance\Core\Errode;

use Dcux\SSO\Kernel\Security;

use Dcux\Api\Data\VUser;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\Api\Kernel\Api;
use Respect\Validation\Validator;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\OAuth2\OAuth2;

class Exchange extends TApi
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
        global $CFG;
        /*$username = $_REQUEST['username'];
        $password = $_REQUEST['password'];*/
        $clientId = $this->params['client_id'];
        $clientSecret = $this->params['client_secret'];
        $requestType = OAuth2::REQUEST_TYPE_PASSWORD;
        $clientType = $clientType = $requestType == OAuth2::REQUEST_TYPE_TOKEN ? OAuth2::CLIENT_TYPE_WEB : ($requestType == OAuth2::REQUEST_TYPE_PASSWORD ? OAuth2::CLIENT_TYPE_DESKTOP : false);
        
        $client = $client = $this->clientService->checkClient($clientId, $clientType, $redirectURI, $clientSecret);
        if ($client) {
            $uid = $this->getUid();
            if ($uid) {
                if (empty($_REQUEST['encode'])) {
                    $this->success(Security::generateSid($uid));
                } else {
                    $this->success(urlencode(Security::generateSid($uid)));
                }
            } else {
                //$this->failure(200103,$CFG['error'][200103]);
                throw new Error(Errode::invalid_user());
            }
        } else {
            throw new Error(Errode::invalid_client());
        }
    }
    public function params()
    {
        global $CFG;
        return array(
            'client_id' => array(
                'validator' => array(Validator::notEmpty(),Validator::in($CFG['super_client_id']))
            ),
            'client_secret'=>array('validator' => array(Validator::notEmpty())
            )
        );
    }
}
// PHP END
