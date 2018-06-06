<?php
namespace Dcux\Api\Action\Account;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VClient;
use Dcux\Api\Kernel\SApi;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\SSO\Service\OAuth2TokenService;

use Respect\Validation\Validator;

// @see http://sso.project.dcux.com/api/app/logout?token=c1ddfc5bc7c988afe1332d48c6a56342
// @deprecated

class Logout extends TokenApi
{
    public function onCreate()
    {
        parent::onCreate();
        $this->oauth2TokenService = OAuth2TokenService::getInstance();
    }
    public function onGet()
    {
        // TODO to do logout
        $tokenArr = $this->getToken();
        $token = empty($tokenArr['oauthToken']) ? '' : $tokenArr['oauthToken'];

        $ret = $this->oauth2TokenService->del($token);
        if (!empty($ret)) {
            $this->success('success');
        } else {
            $this->failure('failure');
        }
    }
    public function onPost()
    {
        $this->onGet();
    }
    protected function params()
    {
        return array(
        );
    }
}
// PHP END
