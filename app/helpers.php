<?php

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

if (!function_exists('unparse_url')) {
    /**
     * 反解析URL
     */
    function unparse_url($parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'].'://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':'.$parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':'.$parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?'.$parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }
}

if (!function_exists('append_url_query')) {
    /**
     * Windows查看进程信息
     */
    function append_url_query($url, $query_array = [])
    {
        $info = parse_url($url);
        $query = [];
        if (!empty($info['query'])) {
            parse_str($info['query'], $query);
        }
        if (!empty($query_array)) {
            foreach ($query_array as $key => $value) {
                $query[$key] = $value;
            }
        }
        $info['query'] = http_build_query($query);

        return unparse_url($info);
    }
}
