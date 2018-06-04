<?php

namespace Dcux\SSO\Kernel;


/**
 * 产生验证码
 *
 * @category core
 * @package classes
 * @subpackage core
 * @author liaiyong <liaiyong@dcux.com>
 * @version 1.0
 * @copyright 2005-2012 dcux Inc.
 * @link http://www.dcux.com
 *      
 */
class VerifyCode {
    /**
     * 获得验证码
     * 
     * @param integer $num            
     * @param integer $w            
     * @param integer $h            
     * @return mixed
     */
    public static function getCode($num = 4, $w = 60, $h = 25) {
        // 字符集 去掉了 0 1 o l O
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
        // 存放产生的验证码字符串
        $code = "";
        for($i = 0; $i < $num; $i ++) {
            $code .= $str[mt_rand(0, strlen($str) - 1)];
        }
        // 将产生的$code存放在session中
        $_SESSION["verifyCode"] = strtolower($code);
        //MemSession::setSession();
        // 消息头
        Header("Content-type: image/PNG");
        $im = imagecreate($w, $h);
        // 字体色
        $font_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
        // 边框色
        $gray = imagecolorallocate($im, 118, 151, 199);
        // 背景色
        $bgcolor = imagecolorallocate($im, 216, 233, 249);
        // 画背景 画一矩形并填充
        imagefilledrectangle($im, 0, 0, $w, $h, $bgcolor);
        // 画边框
        // imagerectangle($im, 0, 0, $w-1, $h-1, $gray);
        // imagefill($im, 0, 0, $bgcolor);
        /*
         * //画干扰点
         * for ($i = 0; $i < mt_rand(60,80); $i++) {
         * imagesetpixel($im, rand(0, $w), rand(0, $h), $font_color);
         * }
         */
        // 画干扰线
        /*
         * for($i = 0;$i < mt_rand(3, 5);$i++) {
         * $line_color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
         * imagearc($im, mt_rand(- $w, $w), mt_rand(- $h, $h), mt_rand(30, $w * 2), mt_rand(20, $h * 2), mt_rand(0, 360), mt_rand(0, 360), $line_color);
         * }
         */
        // 字体大小
        $size = 16;
        // 字符在图片中的X坐标
        $strx = rand(10, 15);
        for($i = 0; $i < $num; $i ++) {
            $strpos = rand(1, 6);
            // imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $font_color);
            @imagefttext($im, $size, mt_rand(- 10, 10), $strx, $strpos + 16, $font_color, App::$_rootpath .'/assets/font/DejaVuSerif-Bold.ttf', substr($code, $i, 1));
            $strx += rand(15, 18);
        }
        imagepng($im);
        imagedestroy($im);
    }
}
?>
