<?php

namespace App\Http\Controllers;

use App\Helper\ApiHelper;
use App\Helper\SecurityHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\Http\Services\OAuth2Service;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class OAuth2Controller extends Controller
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

    public function index()
    {
        $form = new \stdClass;
        $form->code = $this->_request->get('code') ?: '';

        $res = $this->_apiHelper->httpPost('/access_token', ['grant_type' => 'authorization_code', 'code' => $form->code, 'client_id' => 'myawesomeapp', 'client_secret' => 'abc123', 'redirect_uri' => url('/index')]);
        $data = $this->_apiHelper->convert($res);

        return $this->success($data);
    }

    //
    public function auth($ability, $arguments = [])
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
            // if($form->unbind) {
            //     return $this->_oauth2->unbindWeixin($form);
            // }
            if($form->signature) {
                // $safeDecrypt = $this->_securityHelper->urlSafeDecode($form->qrtoken);
                // $decrypt = decrypt($safeDecrypt);
                // if(empty($decrypt)) {
                //     throw new \Exception(QRCODE_ERR_105);
                // }
                // list($form->clientId, $form->socketClientId, $form->timestamp, $form->userId) = explode(',', $decrypt);

                return $this->_oauth2->qrlogin($form);
            }
            return $this->_oauth2->login($form);
        }
    }

    public function access_token()
    {
        $form = new \stdClass;
        $form->clientId = $this->_request->get('client_id') ?: '';
        $form->grantType = $grantType = $this->_request->get('grant_type') ?: '';
        // 使用令牌码
        switch($grantType) {
            case 'authorization_code':
                return $this->_oauth2->authcode($form);
        }

        return $this->success();
    }
}
