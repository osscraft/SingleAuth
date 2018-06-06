<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Action;

use Dcux\SSO\Model\QrCode;
use Dcux\SSO\OAuth2\OAuth2;

class QrCodeService extends Service
{
    private $qrCode;
    public function model()
    {
        return QrCode::getInstance();
    }

    public function gen()
    {
        global $CFG;
        $code = OAuth2::generateCode();
        // 没有任务计划时执行删除已过期授权码
        if (empty($CFG['cron_open'])) {
            $this->clean();
        }
        $qrCodeArr = array();
        $qrCodeArr['code'] = $code;
        $qrCodeArr['time'] = date('Y-m-d H:i:s');
        $qrCodeArr['expires'] = time() + $CFG['qr_code_lifetime'];
        $ret = $this->add($qrCodeArr);
        return empty($ret) ? false : $code;
    }
    public function valid($code)
    {
        $qrCode = $this->get($code);
        return empty($qrCode) ? false : ($qrCode['expires'] < time() ? false : $qrCode);
    }
    public function doScan($code)
    {
        if ($this->isScannable($code)) {
            return $this->upd($code, array('status' => QrCode::STATUS_SCAN));
        } else {
            return false;
        }
    }
    public function doLogin($code)
    {
        if ($this->isLoginable($code)) {
            return $this->upd($code, array('status' => QrCode::STATUS_LOGIN));
        } else {
            return false;
        }
    }
    public function isScannable($code)
    {
        $qrCode = $this->valid($code);
        if (empty($qrCode)) {
            return false;
        } else {
            if ($qrCode['status'] > QrCode::STATUS_SCAN) {
                return false;
            } else {
                return true;
            }
        }
    }
    public function isLoginable($code)
    {
        $qrCode = $this->valid($code);
        if (empty($qrCode)) {
            return false;
        } else {
            if ($qrCode['status'] == QrCode::STATUS_SCAN) {
                return true;
            } else {
                return false;
            }
        }
    }
    /**
     * 清除过期授权码
     */
    public function clean()
    {
        $field1 = $this->model()->toField('expires');
        $ret = $this->model()->db()->delete($field1 . ' < UNIX_TIMESTAMP()');
        return empty($ret) ? false : true;
    }
}
// PHP END
