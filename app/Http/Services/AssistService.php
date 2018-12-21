<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Http\Services;

use App\Entities\ThirdEntity;
use App\Entities\UserEntity;
use App\Helper\SocketHelper;
use App\Repositories\ClientRepository;
use App\Repositories\ScopeRepository;
use App\Repositories\SessionRepository;
use App\Repositories\ThirdRepository;
use App\Repositories\UserClientRepository;
use App\Repositories\UserRepository;
use App\Http\Services\ThirdService;
use App\Http\Services\WeixinService;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Jenssegers\Agent\Facades\Agent;

class AssistService
{
    /**
     * @var SocketHelper
     */
    private $_socketHelper;
    /**
     * @var Request
     */
    private $_request;
    /**
     * @var Store
     */
    private $_session;
    /**
     * @var ClientRepository
     */
    private $_clientRepository;
    /**
     * @var ScopeRepository
     */
    private $_scopeRepository;
    /**
     * @var SessionRepository
     */
    private $_sessionRepository;
    /**
     * @var UserClientRepository
     */
    private $_userClientRepository;
    /**
     * @var UserRepository
     */
    private $_userRepository;
    /**
     * @var ThirdService
     */
    private $_third;
    /**
     * @var WeixinService
     */
    private $_weixin;

    public function __construct(SocketHelper $socketHelper, Request $request, ClientRepository $clientRepository, ScopeRepository $scopeRepository, SessionRepository $sessionRepository, UserClientRepository $userClientRepository, UserRepository $userRepository, ThirdService $third, WeixinService $weixin)
    {
        $this->_socketHelper = $socketHelper;
        $this->_request = $request;
        $this->_session = $request->session();
        $this->_clientRepository = $clientRepository;
        $this->_scopeRepository = $scopeRepository;
        $this->_sessionRepository = $sessionRepository;
        $this->_userClientRepository = $userClientRepository;
        $this->_userRepository = $userRepository;
        $this->_third = $third;
        $this->_weixin = $weixin;
    }

    public function authorize($form)
    {
        $lifetime = env('QRCODE_LOGIN_LIFETIME', 60);
        if(time() - $form->timestamp > $lifetime) {
            // 已经过期
            throw new \Exception(QRCODE_ERR_102);
        }
        $form->client = $client = $this->_clientRepository->getClientEntity($form->clientId, null, null, false);
        $isOnline = $this->_socketHelper->isOnline($form->socketClientId);
        if(empty($isOnline)) {
            throw new \Exception(QRCODE_ERR_103);
        }

        $form->sessionUser = $user = $this->_sessionRepository->getUser();
        if (empty($user)) {
            if ($form->isWeixinBrowser) {
                $form->state = 'STATE';
                $form->backurl = "/qrcode/callback/{$form->encrypt}";
                $targetUrl = $this->_weixin->authorize($form);
                if (!empty($form->show)) {
                    return $targetUrl;
                }
                return redirect($targetUrl);
            } else {
                return view('oauth2.authorize', ['form' => $form]);
            }
        }

        return view('oauth2.authorize', ['form' => $form]);
    }

    public function login($form)
    {
        $maxLoginCount = env('LOGIN_COUNT', 3);
        $form->client = $client = $this->_clientRepository->getClientEntity($form->clientId, null, null, false);
        $form->sessionUser = $user = $this->_sessionRepository->getUser();
        $form->loginCount = $this->_sessionRepository->getLoginCount();
        $form->lastAttemptTime = $this->_sessionRepository->getLastAttemptTime();
        if($form->loginCount > $maxLoginCount && !empty($form->lastAttemptTime) && time() - $form->lastAttemptTime < 1800) {
            // 设置信息
            $form->error = '请半小时后重试';
            return view('oauth2.authorize', ['form' => $form]);
        }
        if(empty($user)) {
            // 验证用户名密码
            $user = $this->_userRepository->getUserEntityByUserCredentials($form->username, $form->password, $grant->getIdentifier(), $client);
            if(empty($user)) {
                // 验证失败
                $this->_sessionRepository->incLoginCount();
                $this->_sessionRepository->persistLastAttemptTime();
                // 设置信息
                $form->error = '用户名或密码错误';
                return view('oauth2.authorize', ['form' => $form]);
            }

            $this->_sessionRepository->persistUser($user);
            $this->_sessionRepository->revokeLoginCount();
            $this->_sessionRepository->revokeLastAttemptTime();
        }

        return view('portal.index', ['form' => $form]);
    }

    public function callback($form)
    {

    }
}
