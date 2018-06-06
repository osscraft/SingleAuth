<?php

namespace Lay\Advance\Http;

use Lay\Advance\Core\Singleton;

// use HttpResponse;
class Response extends Singleton
{
    // use Singleton;
    protected $httpResponse;
    protected $code = Http::OK;
    protected $header = array();
    protected $cookie = array();
    protected $body;
    protected function __construct()
    {
        // $this->httpResponse = new HttpResponse();
    }
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }
    public function execute()
    {
        list($header, $body) = $this->compile();
        // Session::getInstance()->commit();
        if (! headers_sent()) {
            array_map('header', $header);
            $this->header = array();
            foreach ($this->cookie as $config) {
                list($name, $value, $expire, $path, $domain, $secure, $httponly) = $config;
                setCookie($name, $value, $expire, $path, $domain, $secure, $httponly);
            }
            $this->cookie = array();
        }
        if ($body instanceof \Closure) {
            echo call_user_func($body);
        } else {
            echo $body;
        }
    }
    public function setCode($code)
    {
        $this->code = ( int ) $code;
        return $this;
    }
    public function getCode()
    {
        return $this->code;
    }
    public function setCookie($name, $value, $expire = 0, $path = '/', $domain = null, $secure = null, $httponly = true)
    {
        if ($secure === null) {
            $secure = ( bool ) server('https');
        }
        $key = sprintf('%s@%s:%s', $name, $domain, $path);
        $this->cookie[$key] = array(
                $name,
                $value,
                $expire,
                $path,
                $domain,
                $secure,
                $httponly
        );
        return $this;
    }
    public function setHeader($header)
    {
        if (strpos($header, ':')) {
            list($key, $val) = explode(':', $header, 2);
            $this->header[trim($key)] = trim($val);
        } else {
            $this->header[$header] = null;
        }
        return $this;
    }
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    public function getBody()
    {
        return $this->body;
    }
    // return array($header, $body);
    public function compile()
    {
        $body = in_array($this->getCode(), array(
                204,
                301,
                302,
                303,
                304
        )) ? '' : $this->body;
        return array(
                $this->compileHeader(),
                $body
        );
    }
    public function reset()
    {
        $this->code = Http::OK;
        $this->header = array();
        $this->cookie = array();
        $this->body = null;
        // Session::getInstance()->reset();
        return $this;
    }
    public function redirect($url, $code = 303)
    {
        $this->setCode($code)->setHeader('Location: ' . $url);
        return $this;
    }
    // ////////////////// protected method ////////////////////
    protected function compileHeader()
    {
        $header = array();
        $header[] = Http::getStatusHeader($this->code ?  : 200);
        foreach ($this->header as $key => $val) {
            $header[] = $val === null ? $key : $key . ': ' . $val;
        }
        return $header;
    }
}

// PHP END
