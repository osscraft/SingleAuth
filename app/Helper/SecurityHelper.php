<?php

namespace App\Helper;

class SecurityHelper
{
    //对称URL安全的base64加密
    public function urlSafeEncode($string)
    {
        $data = str_replace(array('+','/','='),array('-','_',''),$string);
        return $data;
    }
    //对称URL安全的解密
    public function urlSafeDecode($string)
    {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return $data;
    }

    //对称URL安全的base64加密
    public function urlSafeBase64Encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    //对称URL安全的base64解密
    public function urlSafeBase64Decode($string)
    {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}