<?php

namespace App\Helper;

use App\Helper\Traits\Transmit;
use GatewayWorker\Lib\Gateway;

class SocketHelper
{
    use Transmit;

    const EVENT_ONQRCODE_SCAN = 'onqrcodescan';
    const EVENT_ONQRCODE_LOGIN = 'onqrcodelogin';

    /**
     * @var \App\Http\Helper\LogHelper
     */
    private $_logHelper;

    public function __construct()
    {
        Gateway::$registerAddress = env('SOCKET_SERVER_REGISTER_URL', '127.0.0.1:1236');

        $this->_logHelper = app(LogHelper::class);
    }

    public function isUidOnline($uid)
    {
        return Gateway::isUidOnline($uid);
    }

    // 客户端是否在线
    public function isOnline($clientId)
    {
        return Gateway::isOnline($clientId);
    }
}
