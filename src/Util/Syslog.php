<?php

namespace Dcux\Util;

use Dcux\Util\Utility;

/**
 * 记录系统日志，应用接入日志，资源拥有者登录日志。
 * 
 * @category
 *
 * @package classes
 * @author liangjun <liangjun@dcux.com>
 * @version 1.0
 * @copyright 2005-2012 dcux Inc.
 * @link http://www.dcux.com
 *      
 */
class Syslog {
    /**
     *
     * 记录应用接入日志。
     * 包含应用ID，应用名称，应用URL，应用接入时间，接入操作（有请求访问令牌和请求资源两种操作），资源拥有者，是否成功（失败，则写明失败代号）。
     * api,clientId,responseType,requestType,success
     *
     * @author liangjun@dcux.com
     * @param array $applog            
     * @return void
     */
    public static function logApp($appLog) {
        $arr = &$appLog;
        $message = '';
        $i = 0;
        foreach ( $arr as $k => $v ) {
            if ($i == 0) {
                $message .= "$k=>" . $v;
            } else {
                $message .= ",$k=>" . $v;
            }
            $i ++;
        }
        $message .= ",ip=>" . Utility::ip();
        $message .= ",os=>" . Utility::os();
        $message .= ",browser=>" . Utility::browser();
        //$message .= ",ua=>" . Utility::ua();
        self::logToSyslog(LOG_INFO, 'Client:' . $message);
    }
    
    /**
     *
     * 记录资源拥有者登录的日志。
     * 包含的字段有资源拥有者的用户名，登录时间，登录时的IP地址，从那个应用登录，是否成功。
     * clientId,username,success
     *
     * @author liangjun@dcux.com
     * @param array $resourceOwnerLog            
     * @return void
     */
    public static function logResourceOwner($resourceOwnerLog) {
        $arr = &$resourceOwnerLog;
        $message = '';
        $i = 0;
        foreach ( $arr as $k => $v ) {
            if ($i == 0) {
                $message .= "$k=>" . $v;
            } else {
                $message .= ",$k=>" . $v;
            }
            $i ++;
        }
        $message .= ",ip=>" . Utility::ip();
        $message .= ",os=>" . Utility::os();
        $message .= ",browser=>" . Utility::browser();
        //$message .= ",ua=>" . Utility::ua();
        self::logToSyslog(LOG_INFO, 'User:' . $message);
    }
    
    /**
     *
     * 记录系统中的日志。
     * 包含有系统的运行状况，调试日志。
     *
     * @author liangjun@dcux.com
     * @param string $systemLog            
     * @return void
     */
    public static function logSystem($systemLog) {
        self::logToSyslog(LOG_INFO, $systemLog);
    }
    
    /**
     *
     * 将日志写入系统syslog中。
     * $priority参数请参见http://cn.php.net/manual/zh/function.syslog.php
     *
     * @author liangjun@dcux.com
     * @param int $priority            
     * @param string $message            
     * @return void
     */
    private static function logToSyslog($priority, $message) {
        syslog($priority, "DCUX SSO SYSTEM:" . $message);
    }
}
?>