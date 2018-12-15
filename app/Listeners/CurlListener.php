<?php

namespace App\Listeners;

use App\Events\CurlEvent;
use App\Helper\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CurlListener
{
    /**
     * @var LogHelper
     */
    private $_logHelper;

    public function __construct(LogHelper $logHelper)
    {
        $this->_logHelper = $logHelper;
    }
    /**
     * Handle the event.
     *
     * @param  CurlEvent  $event
     * @return void
     */
    public function handle(CurlEvent $event)
    {
        $info = $event->info;
        $url = $event->url;
        $method = $event->method;
        $headers = [];
        foreach($event->headers as $header) {
            if(preg_match('/^Authorization:.*$/i', $header, $matches) > 0) {
                $headers[] = "Authorization: *";
            } else {
                $headers[] = $header;
            }
        }
        $headerAsJson = json_encode($headers);
        $body = $event->body;
        $code = $info['http_code'];
        $time = round($info['total_time']*1000, 3);
        // 记录Curl日志
        $this->_logHelper->info("Code: $code - {$method} {$url} - Body: {$body} - Header: {$headerAsJson} - Time: {$time}ms", 'CURL');
    }
}
