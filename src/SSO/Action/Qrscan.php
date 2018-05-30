<?php

namespace Dcux\SSO\Action;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;
use Lay\Advance\Core\Encryptor;
use Lay\Advance\Core\Action;
use Lay\Advance\Core\Errode;

use Dcux\SSO\Service\QrCodeService;

use Dcux\Api\Data\VResponse;

class Qrscan extends Action {
    private $qrCodeService;
    public function onCreate() {
        parent::onCreate();
        $this->qrCodeService = QrCodeService::getInstance();
    }
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        global $CFG;
        $sid = empty($_REQUEST['sid']) ? '' : $_REQUEST['sid'];
        $fcid = empty($_REQUEST['cid']) ? 0 : intval($_REQUEST['cid']);
        $sscode = empty($_REQUEST['sscode']) ? '' : $_REQUEST['sscode'];
        $data = array();
		if(empty($sscode)){
			$this->failure(Errode::invalid_request());
		}else{
			$dscode = Encryptor::decrypt($sscode, $CFG['sso_qrlogin_key']);
            $dscode = json_decode($dscode, true);
			if(empty($dscode)) {
                $this->failure(Errode::invalid_sscode());
            } else {
                $cid = intval($dscode['cid']);
                $scode = $dscode['scode'];
                $dcode = Encryptor::decrypt($scode, $CFG['server_qrlogin_key']);
                //$dcode = json_decode($dcode, true);
				if(empty($dcode)) {
                    $this->failure(Errode::invalid_scode());
                } else{
					$handle = stream_socket_client($CFG['server_internal_gateway']);
					if(empty($handle)) {
						$this->failure(Errode::invalid_socket());
					} else{
						if(empty($sid)){
                            if($this->qrCodeService->isScannable($dcode)) {
                                $data['cmd'] = '/qr/scan';
                                $data['data'] = array('cid'=>$cid,'fcid'=>$fcid,'scode'=>$scode);
                                $r = fwrite($handle, json_encode($data)."\n");
                            } else {
                                $this->failure(Errode::invalid_scode());
                            }
						}else{
                            if($this->qrCodeService->isLoginable($dcode)) {
                                //模拟超级用户，以文本协议发送数据，注意Text文本协议末尾有换行符（发送的数据中最好有能识别超级用户的字段），这样在Event.php中的onMessage方法中便能收到这个数据，然后做相应的处理即可
                                $data['cmd'] = '/qr/login';
                                $data['data'] = array('cid'=>$cid,'fcid'=>$fcid,'sid'=>$sid,'scode'=>$scode);
    							$r = fwrite($handle, json_encode($data)."\n");
                            } else {
                                $this->failure(Errode::invalid_scode());
                            }
						}
                        //Logger::debug(array('qrscan', empty($r) ? 0 : $r, $data));
					}
					fclose($handle);
				}
			}
		}

        /*$this->template->push('time', time());
        $this->template->push('sid', $sid);
        $this->template->push('sscode', $sscode);
        $this->template->push('dscode', $dscode);
        $this->template->push('cid', $cid);
        $this->template->push('scode', $scode);
        $this->template->push('dcode', $dcode);
        $this->template->push('qrtime', $qrtime);*/
    }
    protected function failure($code, $msg = null) {
        $this->vresponse = empty($this->vresponse) ? new VResponse() : $this->vresponse;
        $this->vresponse->rsp = 0;
        $this->vresponse->data = $msg;
        $this->vresponse->code = $code;
    }
    protected function success($data) {
        $this->vresponse = empty($this->vresponse) ? new VResponse() : $this->vresponse;
        $this->vresponse->rsp = 1;
        $this->vresponse->code = 0;
        $this->vresponse->data = $data;
    }
    // overide
    public function onRender() {
        $this->vresponse = empty($this->vresponse) ? new VResponse() : $this->vresponse;
        //$this->template->distinct();// clean template vars
        $this->template->push($this->vresponse->toStandard());
        parent::onRender();
    }
}