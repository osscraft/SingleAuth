<?php

namespace App\Http\Controllers;

use App\Helper\ApiHelper;
use App\Helper\SecurityHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\Http\Services\AssistService;
use App\Http\Services\WeixinService;
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
     * @var WeixinService
     */
    private $_weixin;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiHelper $apiHelper, SecurityHelper $securityHelper, Request $request, AssistService $assist, WeixinService $weixin)
    {
        $this->_apiHelper = $apiHelper;
        $this->_securityHelper = $securityHelper;
        $this->_request = $request;
        $this->_session = $request->session();
        $this->_weixin = $weixin;
    }
    
    /**
     * 请求微信授权
     * @demo http://127.0.0.1:8800/third/weixin/authorize
     */
    public function auth($appId)
    {
        $form = new \stdClass;
        $form->backurl = $this->_request->input('backurl') ?: "/api/v2/weixin/backurl/$appId";
        $form->state = $this->_request->input('state') ?: 'STATE';

        $redirect = $this->_weixin->authorize($form);

        return $this->success($redirect);
    }

    /**
     * 请求微信授权回调，统一处理
     * @demo http://127.0.0.1:8800/third/weixin/callback?show=1&code=12941729412&state=STATE&backurl=%2Fthird%2Fweixin%2Fbackurl%3Fsome%3D1
     */
    public function callback($appId = '', $name = '')
    {
        $form = new \stdClass;
        $form->name = $name;
        $form->show = $this->_request->input('show') ?: '';
        $form->code = $this->_request->input('code') ?: '';
        $form->state = $this->_request->input('state') ?: '';
        $form->backurl = $this->_request->input('backurl') ?: '';

        $url = $this->_weixin->callback($form);

        if($form->show) {
            return $url;
        }
        return redirect($url);
    }


    /**
     * 示例实际回调地址
     * @demo http://127.0.0.1:8800/third/weixin/backurl?some=1&code=12941729412&state=STATE
     */
    public function backurl($appId = '')
    {
        $form = new \stdClass;
        $form->code = $this->_request->input('code') ?: '';
        $form->state = $this->_request->input('state') ?: '';
        // 获取用户信息
        $data = $this->_weixin->backurl($form);

        return $this->success($data);
    }
}
