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
    
    /**
     * 对称加密
     */
    public function encrypt($string, $expiry = 0) {
        return $this->authcode($string, 'ENCODE', env('APP_KEY'), $expiry);
    }
    
    /**
     * 对称加密(url安全的Base64解密)
     */
    public function urlSafeEncrypt($string, $expiry = 0) {
        return $this->urlSafeEncode($this->encrypt($string, $expiry));
    }

    /**
     * 对称解密
     */
    public function decrypt($string) {
        return $this->authcode($string, 'DECODE', env('APP_KEY'));
    }
    
    /**
     * 对称加密(url安全的Base64加密)
     */
    public function urlSafeDecrypt($string) {
        return $this->decrypt($this->urlSafeDecode($string));
    }

    /**
     * discuz加解密
     */
    private function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {   
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
        $ckey_length = 4;   
           
        // 密匙a会参与加解密   
        $keya = md5(substr($key, 0, 16));   
        // 密匙b会用来做数据完整性验证   
        $keyb = md5(substr($key, 16, 16));   
        // 密匙c用于变化生成的密文   
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): 
    substr(md5(microtime()), -$ckey_length)) : '';   
        // 参与运算的密匙   
        $cryptkey = $keya.md5($keya.$keyc);   
        $key_length = strlen($cryptkey);   
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)， 
    //解密时会通过这个密匙验证数据完整性   
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确   
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :  
    sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;   
        $string_length = strlen($string);   
        $result = '';   
        $box = range(0, 255);   
        $rndkey = array();   
        // 产生密匙簿   
        for($i = 0; $i <= 255; $i++) {   
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);   
        }   
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度   
        for($j = $i = 0; $i < 256; $i++) {   
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;   
            $tmp = $box[$i];   
            $box[$i] = $box[$j];   
            $box[$j] = $tmp;   
        }   
        // 核心加解密部分   
        for($a = $j = $i = 0; $i < $string_length; $i++) {   
            $a = ($a + 1) % 256;   
            $j = ($j + $box[$a]) % 256;   
            $tmp = $box[$a];   
            $box[$a] = $box[$j];   
            $box[$j] = $tmp;   
            // 从密匙簿得出密匙进行异或，再转成字符   
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));   
        }   
        if($operation == 'DECODE') {  
            // 验证数据有效性，请看未加密明文的格式   
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&  
    substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {   
                return substr($result, 26);   
            } else {   
                return '';   
            }   
        } else {   
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因   
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码   
            return $keyc.str_replace('=', '', base64_encode($result));   
        }   
    }

    /**
     * 生成二维码登录会话令牌
     */
    public function qrcodeLoginToken($form)
    {
        return $this->urlSafeEncrypt("$form->clientId,$form->socketClientId,$form->timestamp");
        // $encrypt = encrypt("$form->clientId,$form->socketClientId,$form->timestamp");
        // return $this->urlSafeEncode($encrypt);
    }

    /**
     * 解析二维码登录会话令牌
     */
    public function resolveQrcodeLoginToken($token)
    {
        // $safeDecrypt = $this->urlSafeDecode($token);
        // $decrypt = decrypt($safeDecrypt);
        $decrypt = $this->urlSafeDecrypt($token);
        if(empty($decrypt)) {
            return false;
        }
        $resolve = explode(',', $decrypt);
        if(count($resolve) < 3) {
            return false;
        }

        return $resolve;
    }

    /**
     * 验证二维码登录会话令牌
     */
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

    /**
     * 二维码登录会话签名
     */
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

    /**
     * 验证二维码登录会话签名
     */
    public function validQrcodeLoginSignature($form)
    {
        return strtoupper($form->signature) === $this->qrcodeLoginSignature($form);
    }
}
