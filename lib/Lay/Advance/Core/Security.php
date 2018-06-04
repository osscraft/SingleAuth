<?php
namespace Lay\Advance\Core;

use Lay\Advance\Core\Encryptor;

//
class Security extends Encryptor {
    public static function generateCode() {
        return md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
    }
    /**
     * 生成一个sid
     * sid是一个字符串的加密字串。他的规则是"[XXX,userid,time]"。
     * 
     * @param long|string $userId  
     * @param string $key
     * @param int $expires 过期时间，默认1天
     * @return string
     */
    public static function generateSid($uid, $key = 'advance', $expires = 86400) {
        $content = array (
                self::confusion(),
                $uid,
                empty($expires) ? 0 : 1,
                time() + $expires
        );
        $content = json_encode($content);
        return self::encrypt($content, $key);
    }
    /**
     * 从sid中获取用户信息。
     *
     * @param string $sid            
     * @return int | boolean 如果sid不合法则返回false。;
     */
    public static function getUidFromSid($sid, $key = 'advance') {
        $ret = self::getInfoFromSid($sid, $key);
        if (empty($ret)) {
            return false;
        } else if(count($ret) > 3 && $ret[2] && $ret[3] < time()){
            return false;
        } else {
            return $ret[1];
        }
    }
    /**
     * 从sid中获取信息。
     *
     * @param string $sid            
     * @return array | boolean 如果sid不合法则返回false。否则返回array('XXX', 'userid', time);
     */
    public static function getInfoFromSid($sid, $key = 'advance') {
        if (trim($sid) == '') {
            return false;
        }

        $content = self::decrypt($sid, $key);
        $ret = json_decode($content, true);
        if (empty($ret)) {
            return false;
        } else {
            return $ret;
        }
    }
}

// PHP END