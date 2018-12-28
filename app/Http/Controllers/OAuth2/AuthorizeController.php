<?php

namespace App\Http\Controllers\OAuth2;

use App\Helper\ApiHelper;
use App\Helper\SecurityHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\Http\Services\OAuth2Service;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Jenssegers\Agent\Facades\Agent;
use League\OAuth2\Client\Provider\GenericProvider;

class AuthorizeController extends Controller
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
     * @var Store
     */
    private $_session;
    /**
     * @var OAuth2Service
     */
    private $_oauth2;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiHelper $apiHelper, SecurityHelper $securityHelper, Request $request, OAuth2Service $oauth2)
    {
        $this->_apiHelper = $apiHelper;
        $this->_securityHelper = $securityHelper;
        $this->_request = $request;
        $this->_session = $request->session();
        $this->_oauth2 = $oauth2;
    }

    /**
     * 请求授权
     */ 
    public function auth()
    {
        $form = new \stdClass;
        $form->clientId = $this->_request->get('client_id') ?: '';
        $form->responseType = $this->_request->get('response_type') ?: '';
        $form->state = $this->_request->get('state') ?: '';
        $form->username = $this->_request->input('username') ?: '';
        $form->password = $this->_request->input('password') ?: '';
        $form->socketServerUri = (is_https() ? 'wss' : 'ws') . '://' . env('SOCKET_SERVER_HOST') . ':' . env('SOCKET_SERVER_PORT');
        $form->qrcodeLifetime = env('QRCODE_LOGIN_LIFETIME', 120);
        $form->isMobile = Agent::isMobile();
        $form->isWeixinBrowser = strpos(Agent::getUserAgent(), 'MicroMessenger') !== false;

        $method = $this->_request->method();
        if($method == 'GET') {
            return $this->_oauth2->authorize($form);
        } else {
            $form->username = $this->_request->post('username') ?: '';
            $form->password = $this->_request->post('password') ?: '';
            $form->logout = $this->_request->input('logout') ?: false;
            $form->unbind = $this->_request->input('unbind') ?: false;
            $form->type = $this->_request->input('type') ?: '';
            $form->signature = $this->_request->input('signature') ?: false;
            $form->nonceStr = $this->_request->input('nonceStr') ?: '';
            $form->error = '';

            if($form->logout) {
                return $this->_oauth2->logout($form);
            }
            
            if($form->signature) {
                return $this->_oauth2->qrlogin($form);
            }

            return $this->_oauth2->login($form);
        }
    }
}
