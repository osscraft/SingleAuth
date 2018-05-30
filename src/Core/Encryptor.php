<?php

namespace Dcux\Core;

use RuntimeException;

//
class Encryptor {
    public static function encrypt($str, $key = 'sso') {
        $iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
        $iv = str_repeat("\0", $iv_size);
        return base64_encode(mcrypt_encrypt(MCRYPT_3DES, $key, $str, MCRYPT_MODE_CFB, $iv));
    }
    public static function decrypt($str, $key = 'sso') {
        if (trim($str) == '') {
            return false;
        }
        $iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
        $iv = str_repeat("\0", $iv_size);
        return mcrypt_decrypt(MCRYPT_3DES, $key, base64_decode($str), MCRYPT_MODE_CFB, $iv);
    }
    /**
     * 随机生成指定长度的混淆码
     * 
     * @param
     *            Integer len
     * @return s {String}
     */
    public static function confusion($len = 2, $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=") {
        $output = "";
        $len = $len > 0 ? $len : 4;
        for($l = 0; $l < $len; $l ++) {
            $pos = mt_rand(0, strlen($str) - 1);
            $output += substr($str, $pos, 1);
        }
        return $output;
    }

    public static function md5_encrypt($str, $key = 'sso') {
        $key    =   md5($key);
        $x      =   0;
        $len    =   strlen($str);
        $l      =   strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) 
            {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($str{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }
    public static function md5_decrypt($str, $key = 'sso') {
        $key = md5($key);
        $x = 0;
        $str = base64_decode($str);
        $len = strlen($str);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($str, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($str, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($str, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }
}

// PHP END