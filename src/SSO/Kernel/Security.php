<?php

namespace Dcux\SSO\Kernel;

use RuntimeException;
use Lay\Advance\Core\Encryptor;

//
class Security extends \Lay\Advance\Core\Security {
    /**
     * 生成一个token
     * token是一个字符串的加密字串。他的规则是"[XXX,userid,time]"。
     * 
     * @param long $userId            
     * @return string
     */
    public static function generateSid($uid, $key = 'advance') {
        global $CFG;
        $content = array (
                self::confusion(),
                $uid,
                time()
        );
        $content = json_encode($content);
        return self::encrypt($content, $key);
    }
    /**
     * 从token中获取用户信息。
     *
     * @param string $token            
     * @return array | boolean 如果token不合法则返回false。否则返回array('XXX', 'userid', time);
     */
    public static function getUidFromSid($token, $key = 'advance') {
        global $CFG;
        if (trim($token) == '') {
            return false;
        }

        $content = self::decrypt($token, $key);
        //$content = '['.$content.']';
        $ret = json_decode($content, true);
        if (empty($ret)) {
            return false;
        } else {
            return $ret[1];
        }
    }
}

// PHP END