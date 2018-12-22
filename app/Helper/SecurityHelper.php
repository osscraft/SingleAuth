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

    public function qrcodeLoginToken($form)
    {
        $encrypt = encrypt("$form->clientId,$form->socketClientId,$form->timestamp");
        return $this->urlSafeEncode($encrypt);
    }

    public function resolveQrcodeLoginToken($token)
    {
        $safeDecrypt = $this->urlSafeDecode($token);
        $decrypt = decrypt($safeDecrypt);
        if(empty($decrypt)) {
            return false;
        }
        $resolve = explode(',', $decrypt);
        if(count($resolve) < 3) {
            return false;
        }

        return $resolve;
    }
    
    public function validQrcodeLoginToken($token)
    {
        $lifetime = env('QRCODE_LOGIN_LIFETIME', 120);
        $resolve = $this->resolveQrcodeLoginToken($token);
        if(!empty($resolve) && !empty($lifetime)) {
            list(,,$timestamp) = $resolve;
            if($timestamp + $lifetime >= time()) {
                return $resolve;
            }
        }

        return false;
    }

    public function qrcodeLoginSignature($form)
    {
        $data = [];
        $data['clientId'] = $form->clientId;
        $data['clientSecret'] = $form->clientSecret;
        $data['nonceStr'] = $form->nonceStr;
        $data['socketClientId'] = $form->socketClientId;
        $data['timestamp'] = $form->timestamp;
        $data['username'] = $form->username;
        ksort($data);

        return strtoupper(sha1(http_build_query($data)));
    }

    public function validQrcodeLoginSignature($form)
    {
        return strtoupper($form->signature) === $this->qrcodeLoginSignature($form);
    }
}
