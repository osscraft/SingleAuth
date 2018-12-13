<?php

namespace App\Helper;

class LogHelper
{
    /**
     * 日志输出
     */
    public function info($log, $name = 'LUMEN')
    {
        if(env("LOG_$name", env("LOG_GLOBAL", true))) {
            $request = app()->request;
            $log = empty($request->requestId) ? $log : "{$request->requestId} - $log";
            app('log')->withName($name)->info($log);
        }
    }

    /**
     * SQL日志输出
     */
    public function sql($log, $name = 'SQL')
    {
        if (env("LOG_SQL", env("LOG_GLOBAL", true))) {
            $request = app()->request;
            $log = empty($request->requestId) ? $log : "{$request->requestId} - $log";
            app('sql-log')->withName($name)->info($log);
        }
    }

    /**
     * 异常日志输出
     */
    public function error($log, $name = 'SYSTEM')
    {
        $request = app()->request;
        $log = empty($request->requestId) ? $log : "{$request->requestId} - $log";
        app('error-log')->withName($name)->error($log);
    }

    /**
     * DEBUG日志输出
     */
    public function debug($log, $name = 'LUMEN')
    {
        $request = app()->request;
        $log = empty($request->requestId) ? $log : "{$request->requestId} - $log";
        app('log')->withName($name)->debug($log);
    }
}