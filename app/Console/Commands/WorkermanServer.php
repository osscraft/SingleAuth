<?php

namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkermanServer extends Command
{
    protected $signature = 'workerman {action} {--d}';

    protected $description = 'Start a Workerman server.';

    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'wk';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    protected function start()
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    protected function startBusinessWorker()
    {
        $worker                  = new BusinessWorker();
        $worker->name            = 'BusinessWorker';
        $worker->count           = 4;
        $worker->registerAddress = '127.0.0.1:1236';
        $worker->eventHandler    = \App\Workerman\Events::class; 
    }

    protected function startGateWay()
    {
        $gateway = new Gateway("websocket://0.0.0.0:2346");
        $gateway->name                 = 'Gateway';
        $gateway->count                = 1;
        $gateway->lanIp                = '127.0.0.1';
        $gateway->startPort            = 2800;
        $gateway->pingInterval         = 10;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData             = '{"event":"ping"}';
        $gateway->registerAddress      = '127.0.0.1:1236';
    }

    protected function startRegister()
    {
        new Register('text://0.0.0.0:1236');
    }
}