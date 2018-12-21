<?php

namespace App\Workerman;

use App\Helper\LogHelper;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Lib\Gateway;

class Message
{
    const EVENT_ONBIND = 'onbind';
    const EVENT_ONUNBIND = 'onunbind';
    const EVENT_ONJOIN = 'onjoin';
    const EVENT_ONLEAVE = 'onleave';
    const EVENT_ONMESSAGE = 'onmessage';
    const EVENT_ONMESSAGE_GROUP = 'onmessagegroup';
    
    /**
     * @param string $clientId
     * @param string $message
     */
    public function handle($clientId, $message)
    {

    }
}
