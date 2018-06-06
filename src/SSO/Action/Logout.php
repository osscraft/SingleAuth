<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;

use Dcux\SSO\Kernel\UAction;
use Dcux\SSO\OAuth2\OAuth2;

class Logout extends UAction
{
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $access_token = empty($_REQUEST['access_token']) ? '' : $_REQUEST['access_token'];
        $redirect_uri = empty($_REQUEST['redirect_uri']) ? '' : $_REQUEST['redirect_uri'];
        $response_type = empty($_REQUEST['response_type']) ? '' : $_REQUEST['response_type'];
        $this->removeSessionUser();
        if ($redirect_uri) {
            $params = array(
                    'success' => 1
            );
            if ($response_type == OAuth2::RESPONSE_TYPE_TOKEN) {
                $this->template->redirect($redirect_uri . '#' . http_build_query($params));
            } else {
                $this->template->redirect($redirect_uri, $params);
            }
        } else {
            $this->template->push('success', 1);
        }
    }
}
// PHP END
