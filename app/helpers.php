<?php

if (!function_exists('is_mobile')) {
    /**
     * 检测是否是手机端
     *      
     * @return boolean
     */
    function is_mobile()
    {
        return false;
    }
}

if (!function_exists('is_cli')) {
    /**
     * 检测是否是CLI模式运行
     *      
     * @return boolean
     */
    function is_cli()
    {
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }
}

if (!function_exists('is_cgi')) {
    /**
     * 检测是否是windows
     *      
     * @return boolean
     */
    function is_windows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
    
if (!function_exists('is_cgi')) {
    /**
     *
     * @return boolean
     */
    function is_cgi()
    {
        return strtoupper(substr(php_sapi_name(), 0, 3)) === 'CGI';
    }
}

if (!function_exists('is_https')) {
    /**
     *
     * @return boolean
     */
    function is_https()
    {
        if(isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == "on") {
                return true;
            }
        }
        return false;
    }
}

if(!function_exists('is_weixin_browser')) {
    /**
     *
     * @return boolean
     */
    function is_weixin_browser()
    {
        $request = app('request');
        $ua = $request->header('user-agent');
        return preg_match('/MicroMessenger/i', $ua) > 0;
    }
}
