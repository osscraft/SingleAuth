<?php

namespace App\Http\Controllers\OAuth2;

use App\Helper\ApiHelper;
use App\Helper\SecurityHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\Http\Services\OAuth2Service;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;
use League\OAuth2\Client\Provider\GenericProvider;

class AccessTokenController extends Controller
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

    /**
     * 获取令牌
     */ 
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
