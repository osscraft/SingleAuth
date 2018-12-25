<?php

namespace App\Http\Controllers\Portal;

use App\Helper\ApiHelper;
use App\Helper\SecurityHelper;
use App\Helper\Traits\Output;
use App\Http\Controllers\Controller;
use App\Http\Services\PortalService;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;
use League\OAuth2\Client\Provider\GenericProvider;

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
     * @var PortalService
     */
    private $_portal;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiHelper $apiHelper, SecurityHelper $securityHelper, Request $request, PortalService $portal)
    {
        $this->_apiHelper = $apiHelper;
        $this->_securityHelper = $securityHelper;
        $this->_request = $request;
        $this->_session = $request->session();
        $this->_portal = $portal;
        $this->_provider = new GenericProvider([
            'clientId'                => 'myawesomeapp',    // The client ID assigned to you by the provider
            'clientSecret'            => 'e99a18c428cb38d5f260853678922e03',   // The client password assigned to you by the provider
            'redirectUri'             => url('/index'),
            'urlAuthorize'            => url('/authorize'),
            'urlAccessToken'          => url('/token'),
            'urlResourceOwnerDetails' => url('/resource'),
        ]);
    }

    public function index()
    {
        $form = new \stdClass;
        $form->show = $this->_request->get('show') ?: false;

        // $res = $this->_apiHelper->httpPost('/access_token', ['grant_type' => 'authorization_code', 'code' => $form->code, 'client_id' => 'myawesomeapp', 'client_secret' => 'abc123', 'redirect_uri' => url('/index')]);
        // $data = $this->_apiHelper->convert($res);

        return $this->_portal->index($form);
    }
}
