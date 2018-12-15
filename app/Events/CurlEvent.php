<?php

namespace App\Events;

class CurlEvent extends Event
{
    public $url;
    public $method;
    public $headers;
    public $body;
    public $info;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($url, $method, $headers, $body, $info)
    {
        $this->url = $url;
        $this->method = $method;
        $this->headers = empty($headers) ? [] : $headers;
        $this->body = $body;
        $this->info = $info;
    }
}
