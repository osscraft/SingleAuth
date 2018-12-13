<?php

namespace App\Listeners;

use App\Events\ExceptionEvent;
use App\Helper\LogHelper;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionListener
{
    /**
     * @var LogHelper
     */
    private $_logHelper;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(LogHelper $logHelper)
    {
        $this->_logHelper = $logHelper;
    }

    /**
     * Handle the event.
     *
     * @param  ExceptionEvent  $event
     * @return void
     */
    public function handle(ExceptionEvent $event)
    {
        $e = $event->exception;
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();

        if(preg_match('/^([0-9a-zA-Z_]+)\|(.+)$/',$message,$result) > 0) {
            // 业务错误信息
            $this->_logHelper->error($message, 'REQUEST');
        } else {
            // 未知异常
            $this->_logHelper->error("$message in $file:$line");
        }
    }
}
