<?php

namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkermanWinServer extends WorkermanServer
{
    protected $signature = 'wokerman:win {action} {--d}';

    protected $description = 'Start a Workerman server.';

    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        // $argv[0] = 'wk';
        // $argv[1] = $action;
        // $argv[2] = $this->option('d') ? '-d' : '';

        $this->startWin($action);
    }

    protected function startWin($action)
    {
        switch(strtolower($action)) {
            case 'register':
                $this->startRegister();
                break;
            case 'gateway':
                $this->startGateWay();
                break;
            case 'business':
                $this->startBusinessWorker();
                break;
        }
        Worker::runAll();
    }

}