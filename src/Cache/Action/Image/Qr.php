<?php

namespace Dcux\Cache\Action\Image;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\CssMinFilter;
use QRcode;

// include qr lib
include_once dirname(App::$_rootpath) . DIRECTORY_SEPARATOR . '/PHPLib/phpqrcode/qrlib.php';

class Qr extends CAction {
    public function onGet() {
        $qrfile = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/tmp';
        //$value = 'http://sso.dcux.com'; //二维码内容
        $qrtime=$_REQUEST['qrtime'];
        //$client_id=$_REQUEST['client_id'];
        $client_id=$_COOKIE['client_id'];
        $value="dcux://qr/sso/login/$client_id/$qrtime/".md5(time());
        //$value="http://sso.project.dcux.com/scan.php?uuid=".md5($uid).'&cid='.$client_id; 
        $errorCorrectionLevel = 'L';//容错级别 
        $matrixPointSize = 6;//生成图片大小 
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
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,  
            $logo_qr_height, $logo_width, $logo_height); 
        } 
        //imagepng($QR, $qrfile);
		$arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/image/qr.png';
		$arr['content'] = file_get_contents($qrfile);
		$this->template->push($arr['content']);
		//$this->push($arr);
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END