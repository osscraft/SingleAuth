<?php

namespace App\Helper;

use App\Helper\Traits\Transmit;
use GatewayWorker\Lib\Gateway;

class SocketHelper
{
    use Transmit;

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
