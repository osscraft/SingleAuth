<?php

namespace Dcux\Cache\Action\Image;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;
use Lay\Advance\Core\Encryptor;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\CssMinFilter;
use QRcode;

// include qr lib
include_once dirname(App::$_rootpath) . DIRECTORY_SEPARATOR . '/PHPLib/phpqrcode/qrlib.php';

class Qrlogin extends CAction
{
    public function onGet()
    {
        global $CFG;
        $qrfile = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/tmp.'.time();
        $scode = $_REQUEST['scode'];
        $cid = $_REQUEST['cid'];
        $sscode = json_encode(array(
            "cid" => $cid,
            "scode" => $scode
        ));
        //二维码内容
        $value = $CFG['sso_qrlogin_url']."?sscode=".urlencode(Encryptor::encrypt($sscode, $CFG['sso_qrlogin_key']));
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 3;//生成图片大小
        //生成二维码图片
        //Utility::createFolders(dirname($qrfile));
        QRcode::png($value, $qrfile, $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = false;//准备好的logo图片

        //已经生成的原始二维码图
        $QR = imagecreatefromstring(file_get_contents($qrfile));
        if ($logo !== false) {
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled(
 
                $QR,
 
                $logo,
 
                $from_width,
 
                $from_width,
 
                0,
 
                0,
 
                $logo_qr_width,
            $logo_qr_height,
 
                $logo_width,
 
                $logo_height
 
            );
        }
        //imagepng($QR, $qrfile);
        $arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/image/qrlogin.png';
        $arr['content'] = file_get_contents($qrfile);
        $this->template->push($arr['content']);
        //$this->push($arr);
        @unlink($qrfile);
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
