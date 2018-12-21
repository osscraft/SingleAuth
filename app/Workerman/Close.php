<?php

namespace App\Workerman;

use App\Helper\LogHelper;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Lib\Gateway;

class Close
{
    const EVENT_ONCLOSE = 'onclose';

    /**
     * @param string $clientId
     */
    public function handle($clientId)
    {

    }
}
