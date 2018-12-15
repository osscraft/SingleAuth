<?php

namespace App\Helper\Traits;

use App\Events\CurlEvent;
use Illuminate\Support\Str;

/**
 * CURL调用.
 */
trait Curl
{
    /**
     * GET请求
     */
    public function curlGet($url, $headers = [])
    {
        $ua = app('request')->header('user-agent');
        $ua = Str::endsWith($ua, 'CURL') ? : "{$ua} CURL";
        $headers = is_object($headers) ? json_decode(json_encode($headers), true) : $headers;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        if (false !== stripos($url, 'https://')) {
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //严格校验
        }
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        if(!curl_errno($ch)) {
        }
        event(new CurlEvent($url, 'GET', $headers, '', $info));
        curl_close($ch);

        return $result;
    }

    /**
     * POST请求
     */
    public function curlPost($url, $data = [], $headers = [])
    {
        $ua = app('request')->header('user-agent');
        $ua = Str::endsWith($ua, 'CURL') ? : "{$ua} CURL";
        // 对象转数组
        $data = is_object($data) ? json_decode(json_encode($data), true) : $data;
        $headers = is_object($headers) ? json_decode(json_encode($headers), true) : $headers;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        if (false !== stripos($url, 'https://')) {
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //严格校验
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_string($data) ? $data : http_build_query($data));
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        if(!curl_errno($ch)) {
        }
        event(new CurlEvent($url, 'POST', $headers, is_string($data) ? $data : http_build_query($data), $info));
        curl_close($ch);

        return $result;
    }
}
