<?php
namespace Dcux\Api\Action\App;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VClient;
use Dcux\Api\Kernel\SApi;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\SSO\Service\ClientService;

use Respect\Validation\Validator;

// @see http://sso.project.dcux.com/api/app/info?token=c1ddfc5bc7c988afe1332d48c6a56342&cid=ufsso_dcux_portal

class Info extends TokenApi
{
    public function onCreate()
    {
        parent::onCreate();
        $this->clientService = ClientService::getInstance();
    }
    public function onGet()
    {
        $cid = $this->params['cid'];

        $exsits = $this->clientService->getByUnique($cid);

        if (!empty($exsits)) {
            $vc = VClient::parse($exsits);//print_r($vsud);exit;
            $this->success($vc);
        } else {
            $this->failure(Errode::client_not_exists());
        }
    }
    public function onPost()
    {
        $this->onGet();
    }
    protected function params()
    {
        return array(
            'cid' => array(
                'validator' => array(Validator::notEmpty())
            )
        );
    }
}
// PHP END
