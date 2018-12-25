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
use App\Helper\SecurityHelper;
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
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssistService
{
    /**
     * @var SecurityHelper
     */
    private $_securityHelper;
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

    public function __construct(SecurityHelper $securityHelper, SocketHelper $socketHelper, Request $request, ClientRepository $clientRepository, ScopeRepository $scopeRepository, SessionRepository $sessionRepository, UserClientRepository $userClientRepository, UserRepository $userRepository, ThirdService $third, WeixinService $weixin)
    {
        $this->_securityHelper = $securityHelper;
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

    public function qrcode($form)
    {
        $token = $this->_securityHelper->qrcodeLoginToken($form);
        $barcode = url("qrcode/authorize/{$token}");// ->errorCorrection('H')
        $response = QrCode::format('png')->margin(1)->size($form->size)->encoding('UTF-8')->generate($barcode);
        // 
        $this->_sessionRepository->pesistToken($token);

        if($form->show) {
            return $barcode;
        }

        return response($response, 200, ['Content-Length' => strlen($response), 'Content-Type' => 'image/png']);
    }

    public function authorize($form)
    {
        $resolve = $this->_securityHelper->validQrcodeLoginToken($form->encrypt);
        if(empty($resolve)) {
            throw new \Exception(QRCODE_ERR_101);
        }
        list($form->clientId, $form->socketClientId, $form->timestamp) = $resolve;

        $isOnline = $this->_socketHelper->isOnline($form->socketClientId);
        if(empty($isOnline)) {
            throw new \Exception(QRCODE_ERR_103);
        }
        
        // 确认扫描
        $event = SocketHelper::EVENT_ONQRCODE_SCAN;
        $this->_socketHelper->sendToClient($form->socketClientId, $event, []);

        $form->client = $client = $this->_clientRepository->getClientEntity($form->clientId, null, null, false);
        $form->sessionUser = $user = $this->_sessionRepository->getUser();
        if (empty($user)) {
            if ($form->isWeixinBrowser) {
                $this->_third->init('weixin');

                $form->state = 'STATE';
                $form->backurl = "/qrcode/callback/weixin/{$form->encrypt}";
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
        $maxLoginCount = env('LOGIN_ATTEMPT_COUNT', 3);
        $resolve = $this->_securityHelper->validQrcodeLoginToken($form->encrypt);
        if(empty($resolve)) {
            throw new \Exception(QRCODE_ERR_101);
        }
        list($form->clientId, $form->socketClientId, $form->timestamp) = $resolve;

        $isOnline = $this->_socketHelper->isOnline($form->socketClientId);
        if(empty($isOnline)) {
            throw new \Exception(QRCODE_ERR_103);
        }

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
            $user = $this->_userRepository->getUserEntityByUserCredentials($form->username, $form->password, '', $client);
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

        // 确认登录
        $form->type = $event = SocketHelper::EVENT_ONQRCODE_LOGIN;
        $form->username = $username = $user->getUsername();
        $form->clientSecret = $this->_clientRepository->getClientSecret($form->clientId);
        $form->nonceStr = $nonceStr = Str::random();
        $signature = $this->_securityHelper->qrcodeLoginSignature($form);
        $this->_socketHelper->sendToClient($form->socketClientId, $event, ['type' => $event, 'username' => $username, 'nonceStr' => $nonceStr, 'signature' => $signature]);

        return view('assist.success', ['form' => $form]);
    }

    public function logout($form)
    {
        $resolve = $this->_securityHelper->validQrcodeLoginToken($form->encrypt);
        if(empty($resolve)) {
            throw new \Exception(QRCODE_ERR_101);
        }
        list($form->clientId, $form->socketClientId, $form->timestamp) = $resolve;

        $isOnline = $this->_socketHelper->isOnline($form->socketClientId);
        if(empty($isOnline)) {
            throw new \Exception(QRCODE_ERR_103);
        }
        
        // 
        $this->_sessionRepository->revokeUser();

        $form->client = $client = $this->_clientRepository->getClientEntity($form->clientId, null, null, false);
        $form->sessionUser = $sessionUser = $this->_sessionRepository->getUser();
        // 显示页面
        return view('oauth2.authorize', ['form' => $form]);
    }

    public function callback($form)
    {
        $resolve = $this->_securityHelper->validQrcodeLoginToken($form->encrypt);
        if(empty($resolve)) {
            throw new \Exception(QRCODE_ERR_101);
        }
        list($form->clientId, $form->socketClientId, $form->timestamp) = $resolve;

        $isOnline = $this->_socketHelper->isOnline($form->socketClientId);
        if(empty($isOnline)) {
            throw new \Exception(QRCODE_ERR_103);
        }

        if($form->thirdId == 'weixin') {
            $this->_third->init('weixin');
            $third = $this->_third->entity();

            $thirdUser = $this->_weixin->snsUserInfo();

            $this->_sessionRepository->pesistThirdUser($third, $thirdUser);
        } else {
            throw new \Exception(QRCODE_ERR_104);
        }
        // 是否已经绑定过
        $form->isBound = $this->_userRepository->isBound($third, $thirdUser);

        return view('oauth2.authorize', ['form' => $form]);
    }

    public function bind($form)
    {
        $resolve = $this->_securityHelper->validQrcodeLoginToken($form->encrypt);
        if(empty($resolve)) {
            throw new \Exception(QRCODE_ERR_101);
        }
        list($form->clientId, $form->socketClientId, $form->timestamp) = $resolve;

        $isOnline = $this->_socketHelper->isOnline($form->socketClientId);
        if(empty($isOnline)) {
            throw new \Exception(QRCODE_ERR_103);
        }

    }
}
