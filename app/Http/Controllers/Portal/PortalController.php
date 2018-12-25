<?php

namespace App\Http\Controllers\Portal;

use App\Helper\ApiHelper;
use App\Helper\SecurityHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\Http\Services\OAuth2Service;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class PortalController extends Controller
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
}
