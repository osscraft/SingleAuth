<?php

namespace App\Workerman;

use App\Helper\LogHelper;
use App\Helper\Traits\Transmit;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Lib\Gateway;

class Connect
{
    use Transmit;

    const EVENT_ONCONNECT = 'onconnect';

    /**
     * @param string $clientId
     */
    public function handle($clientId)
    {
        $this->sendToCurrentClient(self::EVENT_ONCONNECT, ['clientId' => $clientId]);
    }
}
