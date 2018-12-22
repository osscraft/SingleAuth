<?php

namespace App\Http\Controllers;

use App\Helper\ApiHelper;
use App\Helper\SecurityHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\Http\Services\AssistService;
use App\Http\Services\OAuth2Service;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssistController extends Controller
{
    use Output;
    /**
     * @var ApiHelper
     */
    private $_apiHelper;
    /**
     * @var SecurityHelper
     */
    private $_securityHelper;
    /**
     * @var Request
     */
    private $_request;
    /**
     * @var OAuth2Service
     */
    private $_oauth2;
    /**
     * @var AssistService
     */
    private $_assist;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiHelper $apiHelper, SecurityHelper $securityHelper, Request $request, AssistService $assist, OAuth2Service $oauth2)
    {
        $this->_apiHelper = $apiHelper;
        $this->_securityHelper = $securityHelper;
        $this->_request = $request;
        $this->_session = $request->session();
        $this->_assist = $assist;
        $this->_oauth2 = $oauth2;
    }

    public function qrcode($clientId, $socketClientId, $size = 500)
    {
        $form = new \stdClass;
        $form->show = $this->_request->get('code') ?: 0;
        $form->clientId = $clientId;
        $form->socketClientId = $socketClientId;
        $form->timestamp = time();
        $form->size = $size;

        return $this->_assist->qrcode($form);
    }

    public function qrcodeAuthorize($encrypt)
    {
        $form = new \stdClass;
        $form->encrypt = $encrypt;
        $form->show = $this->_request->get('show') ?: 0;
        $form->username = $this->_request->input('username') ?: '';
        $form->password = $this->_request->input('password') ?: '';
        $form->logout = $this->_request->input('logout') ?: false;
        $form->unbind = $this->_request->input('unbind') ?: false;
        // $form->qrtoken = $this->_request->input('qrtoken') ?: false;
        $form->socketServerUri = (is_https() ? 'wss' : 'ws') . '://' . env('SOCKET_SERVER_HOST') . ':' . env('SOCKET_SERVER_PORT');
        $form->qrcodeLifetime = env('QRCODE_LOGIN_LIFETIME', 120);
        $form->isMobile = Agent::isMobile();
        $form->isWeixinBrowser = strpos(Agent::getUserAgent(), 'MicroMessenger') !== false;

        $method = $this->_request->method();
        if ($method == 'GET') {
            return $this->_assist->authorize($form);
        }

        if($form->logout) {
            return $this->_assist->logout($form);
        }
        
        return $this->_assist->login($form);
    }

    public function qrcodeCallback($thirdId, $encrypt)
    {
        $form = new \stdClass;
        $form->thirdId = $thirdId;
        $form->encrypt = $encrypt;
        
        return $this->_assist->callback($form);
    }
}
