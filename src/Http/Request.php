<?php

namespace Dcux\Http;

use Dcux\Core\Singleton;

// use HttpRequest;
class Request extends Singleton
{
    // use Singleton;
    protected $httpRequest;
    protected $method;
    protected $pathinfo;
    protected $request_uri;
    protected function __construct()
    {
        // $this->httpRequest = new HttpRequest();
    }
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }
    public function getHeader($key)
    {
        $key = 'http_' . str_replace('-', '_', $key);
        return $this->server($key);
    }
    public function getRequestURI()
    {
        if ($this->request_uri) {
            return $this->request_uri;
        }
        if ($uri = $this->server('request_uri')) {
            return $this->request_uri = $uri;
        }
        if ($uri = $this->server('script_filename')) {
            return $this->request_uri = $uri;
        }
        throw new \RuntimeException('Unknown request URI');
    }
    public function getPathinfo()
    {
        if ($this->pathinfo) {
            return $this->pathinfo;
        }
        if ($pathinfo = pathinfo(preg_replace('/^(.*)(\?)(.*)$/', '$1', $this->getRequestURI()))) {
            return $this->pathinfo = $pathinfo;
        }
        throw new \RuntimeException('Unknown request pathinfo');
    }
    public function getMethod()
    {
        if ($this->method) {
            return $this->method;
        }
        $method = strtoupper($this->header('x-http-method-override') ?  : $this->server('request_method'));
        if ($method != 'POST') {
            return $this->method = $method;
        }
        // 某些js库的ajax封装使用这种方式
        $method = $this->post('_method') ?  : $method;
        unset($_POST['_method']);
        return $this->method = strtoupper($method);
    }
    public function getExtension()
    {
        $path = parse_url($this->requestUri(), PHP_URL_PATH);
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?  : 'html';
    }
    public function isGET()
    {
        return ($this->method() === 'GET') ?  : $this->isHEAD();
    }
    public function isPOST()
    {
        return $this->method() === 'POST';
    }
    public function isPUT()
    {
        return $this->method() === 'PUT';
    }
    public function isDELETE()
    {
        return $this->method() === 'DELETE';
    }
    public function isHEAD()
    {
        return $this->method() === 'HEAD';
    }
    public function isAJAX()
    {
        return strtolower($this->header('X_REQUESTED_WITH')) == 'xmlhttprequest';
    }
    public function getReferer()
    {
        return $this->server('http_referer');
    }
    public function getIP($proxy = null)
    {
        $ip = $proxy ? $this->server('http_x_forwarded_for') ?  : $this->server('remote_addr') : $this->server('remote_addr');
        if (strpos($ip, ',') === false) {
            return $ip;
        }
        // private ip range, ip2long()
        $private = array(
                array(
                        0,
                        50331647
                ), // 0.0.0.0, 2.255.255.255
                array(
                        167772160,
                        184549375
                ), // 10.0.0.0, 10.255.255.255
                array(
                        2130706432,
                        2147483647
                ), // 127.0.0.0, 127.255.255.255
                array(
                        2851995648,
                        2852061183
                ), // 169.254.0.0, 169.254.255.255
                array(
                        2886729728,
                        2887778303
                ), // 172.16.0.0, 172.31.255.255
                array(
                        3221225984,
                        3221226239
                ), // 192.0.2.0, 192.0.2.255
                array(
                        3232235520,
                        3232301055
                ), // 192.168.0.0, 192.168.255.255
                array(
                        4294967040,
                        4294967295
                )
        ) // 255.255.255.0 255.255.255.255
;
        $ip_set = array_map('trim', explode(',', $ip));
        // 检查是否私有地址，如果不是就直接返回
        foreach ($ip_set as $key => $ip) {
            $long = ip2long($ip);
            if ($long === false) {
                unset($ip_set[$key]);
                continue;
            }
            $is_private = false;
            foreach ($private as $m) {
                list($min, $max) = $m;
                if ($long >= $min && $long <= $max) {
                    $is_private = true;
                    break;
                }
            }
            if (! $is_private) {
                return $ip;
            }
        }
        return array_shift($ip_set) ?  : '0.0.0.0';
    }
    public function getAcceptTypes()
    {
        return $this->getAccept('http_accept');
    }
    public function getAcceptLanguage()
    {
        return $this->getAccept('http_accept_language');
    }
    public function getAcceptCharset()
    {
        return $this->getAccept('http_accept_charset');
    }
    public function getAcceptEncoding()
    {
        return $this->getAccept('http_accept_encoding');
    }
    public function isAcceptType($type)
    {
        return $this->isAccept($type, $this->getAcceptTypes());
    }
    public function isAcceptLanguage($lang)
    {
        return $this->isAccept($lang, $this->getAcceptLanguage());
    }
    public function isAcceptCharset($charset)
    {
        return $this->isAccept($charset, $this->getAcceptCharset());
    }
    public function isAcceptEncoding($encoding)
    {
        return $this->isAccept($encoding, $this->getAcceptEncoding());
    }
    // ////////////////// protected method ////////////////////
    protected function getAccept($header_key)
    {
        if (! $accept = $this->server($header_key)) {
            return array();
        }
        $result = array();
        $accept = strtolower($accept);
        foreach (explode(',', $accept) as $accept) {
            if (($pos = strpos($accept, ';')) !== false) {
                $accept = substr($accept, 0, $pos);
            }
            $result[] = trim($accept);
        }
        return $result;
    }
    protected function isAccept($find, array $accept)
    {
        return in_array(strtolower($find), $accept, true);
    }
    /**
     *
     * @deprecated
     *
     */
    public function ip($proxy = null)
    {
        return $this->getIP($proxy);
    }
    /**
     *
     * @deprecated
     *
     */
    public function referer()
    {
        return $this->getReferer();
    }
    /**
     *
     * @deprecated
     *
     */
    public function method()
    {
        return $this->getMethod();
    }
    /**
     *
     * @deprecated
     *
     */
    public function extension()
    {
        return $this->getExtension();
    }
    /**
     *
     * @deprecated
     *
     */
    public function requestUri()
    {
        return $this->getRequestURI();
    }
    /**
     *
     * @deprecated
     *
     */
    public function header($key)
    {
        return $this->getHeader($key);
    }
    public function get($key = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
    public function post($key = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }
    public function cookie($key = null)
    {
        if ($key === null) {
            return $_COOKIE;
        }
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
    public function put($key = null)
    {
        static $_PUT = null;
        if ($_PUT === null) {
            if (self::req()->isPUT()) {
                if (strtoupper($this->server('request_method')) == 'PUT') {
                    parse_str(file_get_contents('php://input'), $_PUT);
                } else {
                    $_PUT = & $_POST;
                }
            } else {
                $_PUT = array();
            }
        }
        if ($key === null) {
            return $_PUT;
        }
        return isset($_PUT[$key]) ? $_PUT[$key] : null;
    }
    public function request($key = null)
    {
        if ($key === null) {
            return array_merge(put(), $_REQUEST);
        }
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : put($key);
    }
    public function has_get($key)
    {
        return array_key_exists($key, $_GET);
    }
    public function has_post($key)
    {
        return array_key_exists($key, $_POST);
    }
    public function has_put($key)
    {
        return array_key_exists($key, self::put());
    }
    public function has_request($key)
    {
        return array_key_exists($key, $_REQUEST);
    }
    public function env($key = null)
    {
        if ($key === null) {
            return $_ENV;
        }
        $key = strtoupper($key);
        return isset($_ENV[$key]) ? $_ENV[$key] : false;
    }
    public function server($key = null)
    {
        if ($key === null) {
            return $_SERVER;
        }
        $key = strtoupper($key);
        return isset($_SERVER[$key]) ? $_SERVER[$key] : false;
    }
}

// PHP END
