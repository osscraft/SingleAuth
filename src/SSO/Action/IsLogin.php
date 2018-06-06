<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;
use Lay\Advance\Util\Syslog;

use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Kernel\UAction;

class IsLogin extends UAction
{
    protected function logVisit($client, $response_type, $valid = true, $grant_type = null)
    {
        global $CFG;
        $client_id = empty($client['clientId']) ? '' : $client['clientId'];
        $client_type = empty($client['clientType']) ? '' : $client['clientType'];
        $redirect_uri = empty($client['redirectURI']) ? '' : $client['redirectURI'];
        if ($valid) {
            if (empty($CFG['cron_open'])) {
                StatClientService::addByDay(array(
                        'date' => date("Y-m-d", time()),
                        'clientId' => $client_id
                ), 0);
            }
        }
        if (!empty($CFG['log_by_syslog'])) {
            $arr = array(
                'client_id'=>$client_id,
                'client_type'=>$client_type,
                'response_type'=>$response_type,
                'redirect_uri'=>$redirect_uri,
                'success'=>$valid
            );
            if (!is_null($grant_type)) {
                $arr['grant_type'] = $grant_type;
            }
            Syslog::logApp($arr);
        }
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        global $CFG;
        if (! empty($_SESSION['uid'])) {
            $resource['username'] = $_SESSION['uid'];
            $this->template->push($resource);
            
            $client = array();
            $client['clientId'] = $CFG['SSO_CLIENT_ID'];
            $client['clientType'] = $CFG['SSO_CLIENT_TYPE'];
            $client['redirectURI'] = $CFG['SSO_REDIRECT_URI'];
            $response_type = $client['clientType'] == $CFG['client_type'][2] ? $CFG['response_type'][1] : $CFG['response_type'][0];
            $this->logVisit($client, $response_type);
        } else {
            $this->errorResponse($CFG['LANG']['ERROR']['NOT_LOGGED_IN']);
        }
    }
}
// PHP END
