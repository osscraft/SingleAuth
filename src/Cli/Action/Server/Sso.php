<?php

namespace Dcux\Cli\Action\Server;

use Dcux\Cli\Kernel\CliAction;
use Dcux\SSO\Service\SessionService;
use Dcux\SSO\Service\StatService;

use Workerman\Worker;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;

class Sso extends CliAction {
    protected $statService;
    public function onCreate() {
        parent::onCreate();
        $this->statService = StatService::getInstance();
    }
    public function on() {
        // 创建一个Worker监听2346端口，使用websocket协议通讯
        $ws_worker = new Worker("websocket://0.0.0.0:2346");

        // 启动4个进程对外提供服务
        $ws_worker->count = 4;

        // 当收到客户端发来的数据后返回hello $data给客户端
        $ws_worker->onMessage = function($connection, $data) {
            // 向客户端发送hello $data
            $connection->send('hello ' . $data);
        };

        // 运行worker
        Worker::runAll();
    }

    public function gateway() {
        // gateway 进程
        $gateway = new Gateway("Websocket://0.0.0.0:2347");
        // gateway名称，status方便查看
        $gateway->name = 'SsoGateway';
        // gateway进程数
        $gateway->count = 4;
        // 本机ip，分布式部署时使用内网ip
        //$gateway->lanIp = '127.0.0.1';
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4001 4002 4003 4004 4个端口作为内部通讯端口 
        $gateway->startPort = 2000;
        // 心跳间隔
        $gateway->pingInterval = 10;
        // 心跳数据
        $gateway->pingData = '{"type":"ping"}';
    }

    public function businessworker() {
        // bussinessWorker 进程
        $worker = new BusinessWorker();
        // worker名称
        $worker->name = 'SsoBusinessWorker';
        // bussinessWorker进程数量
        $worker->count = 4;
    }
}