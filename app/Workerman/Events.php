<?php

namespace App\Workerman;

use App\Helper\LogHelper;
use App\Workerman\Close;
use App\Workerman\Connect;
use App\Workerman\Message;
use GatewayWorker\BusinessWorker;

class Events
{
    /**
     * @var LogHelper
     */
    private static $_logHelper;

    /**
     * @param BusinessWorker $businessWorker
     */
    public static function onWorkerStart($businessWorker)
    {
        app(LogHelper::class)->info("Woker: {$businessWorker->workerId}", 'WORKERMAN.START');
    }

    public static function onConnect($clientId)
    {
        app(LogHelper::class)->info("Client: {$clientId}", 'WORKERMAN.CONNECT');

        app(Connect::class)->handle($clientId);
    }

    public static function onWebSocketConnect($clientId, $data)
    {
        app(LogHelper::class)->info("Client: {$clientId}", 'WORKERMAN.CONNECT.WEBSOCKET');
    }

    public static function onMessage($clientId, $message)
    {
        app(LogHelper::class)->info("Client: {$clientId}", 'WORKERMAN.MESSAGE');
        
        app(Message::class)->handle($clientId, $message);
    }

    public static function onClose($clientId)
    {
        app(LogHelper::class)->info("Client: {$clientId}", 'WORKERMAN.CLOSE');
        
        app(Close::class)->handle($clientId);
    }
}
