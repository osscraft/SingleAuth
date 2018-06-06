<?php
namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Dcux\SSO\Kernel\UAction;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\IdentifyService;
use Dcux\SSO\Service\ScopeService;
use Dcux\SSO\Service\OAuth2TokenService;
use Dcux\SSO\Service\ClientService;

class Login extends UAction
{
    protected $identifyService;
    protected $scopeService;
    protected $oauth2TokenService;
    protected $clientService;
    public function onCreate()
    {
        parent::onCreate();
        //$this->userService = MysqlUserService::getInstance();
        $this->identifyService = IdentifyService::getInstance();
        $this->scopeService = ScopeService::getInstance();
        $this->oauth2TokenService = OAuth2TokenService::getInstance();
        $this->clientService = ClientService::getInstance();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        //$uid = $this->getUid();
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $requestType = OAuth2::getRequestType();
        $clientId = OAuth2::getClientId();
        $clientType = $requestType == OAuth2::REQUEST_TYPE_TOKEN ? OAuth2::CLIENT_TYPE_WEB : ($requestType == OAuth2::REQUEST_TYPE_PASSWORD ? OAuth2::CLIENT_TYPE_DESKTOP : false);
        $clientSecret = OAuth2::getClientSecret();
        $redirectURI = OAuth2::getRedirectURI();
        $redirectURI = trim($redirectURI);
        $check = OAuth2::checkRequest($requestType);
        $checkClient = in_array($clientId, $CFG['client_id']);
        if ($check && $checkClient) {
            $client = $this->clientService->checkClient($clientId, $clientType, $redirectURI, $clientSecret);
            if ($client&&$requestType == OAuth2::REQUEST_TYPE_PASSWORD && $password) {
                // log visit count
                $this->logVisit($client, $responseType);
                list($scopeStr, $scopeArr) = $this->scopeService->filter($client['clientScope']);
                $user = $this->identifyService->verifyResourceOwner($username, $password, $client['clientScope']);
                if ($user) {
                    list($scopeStr, $scopeArr) = $this->scopeService->filter($client['clientScope']);
                    $this->updateSessionUser($user);
                    //$params = $this->genTokenParam($user, $client, $scopeStr);
                    $uid = $user['uid'];
                    if (empty($_REQUEST['encode'])) {
                        $this->template->push("sid", Security::generateSid($uid));
                    } else {
                        $this->template->push("sid", urlencode(Security::generateSid($uid)));
                    }
                    $this->logLogin($client, $username, true);
                } else {
                    $this->errorResponse('invalid_grant');
                    $this->logLogin($client, $username, false);
                }
            }
        }
    }
}
// PHP END
