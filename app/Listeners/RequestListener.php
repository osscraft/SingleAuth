<?php

namespace App\Listeners;

use App\Events\RequestEvent;
use App\Helper\LogHelper;
use Illuminate\Http\Request;

class RequestListener
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
     * @param  RequestEvent  $event
     * @return void
     */
    public function handle(RequestEvent $event)
    {
        // 记录Request日志
        $this->logRequest($event->request);
    }

    /**
     * 记录请求日志
     */
    private function logRequest(Request $request)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();
        $ip = $request->ip();
        $ua = $request->header('User-Agent');

        $requestId = $request->requestId;

        $post = $request->request->all();
        $query = $request->query->all();
        $content = $request->getContent();
        if(!empty($content) && empty($post)) {
            // 非标准请求体，如XML
            $bodyAsJson = str_replace("\n", '', $content);
        } else {
            $body = $request->except(config('http.except')) ? : new \stdClass;
            $bodyAsJson = json_encode($body);
        }
        $queryAsJson = json_encode($query);
        $header = [];
        // $appid = $request->header('Appid');
        $token = $request->header('Authorization');
        // if($appid) {
        //     $header[] = "Appid: {$appid}";
        // }
        if($token) {
            $header[] = "Authorization: *";
        }
        $headerAsJson = json_encode($header);

        $files = [];
        foreach($request->files->all() as $file) {
            if(is_array($file)) {
                foreach($file as $f) {
                    $files[] = $f->getRealPath();
                }
            } else {
                $files[] = $file->getRealPath();
            }
        }

        $message = "{$requestId} - IP: {$ip} - {$method} {$uri} - Query: {$queryAsJson} - Header: {$headerAsJson} - Body: {$bodyAsJson} - Files: ".implode(', ', $files)." User-Agent: {$ua}";

        app('log')->withName('REQUEST')->info($message);
    }
}
