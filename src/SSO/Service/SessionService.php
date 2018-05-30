<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Action;

use Dcux\SSO\Model\Session;

class SessionService extends Service {
	private $session;
    private $raw = null;
    public function model() {
        return Session::getInstance();
    }
    protected function __construct() {
    	parent::__construct();
    	$this->session = Session::getInstance();
    }

    public function getOnlineUserCount() {
        $condition = array();
        $condition['online'] = 1;
        $ret = $this->count($condition);
        return empty($ret) ? 0 : $ret;
    }
    
    public function open($savePath, $sessionName) {
        $this->model()->db();
        return true;
    }
    public function close() {
        return $this->model()->db()->close();
    }
    public function read($sessionId) {
        $ret = $this->raw = $this->get($sessionId);
        return empty($ret) || empty($ret['data']) ? false : $ret['data'];
    }
    public function readByMysql($sessionId) {
        $ret = $this->model()->db()->get($sessionId);
        return empty($ret) || empty($ret['data']) ? false : $ret['data'];
    }
    public function readByMemcache($sessionId) {
        $cacher = $this->model()->cacher();
        $ret = empty($cacher) ? false : $cacher->get($sessionId);
        return empty($ret) || empty($ret['data']) ? false : $ret['data'];
    }
    public function write($sessionId, $data) {
        global $CFG;
        $expires = time() + $CFG['mysql_session_lifetime'];
        $datetime = date('Y-m-d H:i:s');
        $time = time();
        $arr = array();
        $arr['id'] = $sessionId;
        $arr['data'] = $data;
        $arr['online'] = ! empty($_SESSION['uid']) ? 1 : 0;
        $arr['expires'] = $expires - 15;// 提前15秒过期，以便数据库中的数据清除

        if(!empty($this->raw)) {
            $arr['time'] = $this->raw['time'];// 存在session数据时，创建时间不变
        } else {
            $arr['time'] = $datetime;// 不存在session数据时，创建时间
        }

        if(!empty($this->raw) && !empty($data) && $data == $this->raw['data']) {//session无变化时
            $rexpires = $this->raw['expires'];
            if(!empty($CFG['mysql_session_keep'])) {
                //增加延迟写入时间
                if(empty($CFG['mysql_session_delay'])) {
                    // 无delay，实时更新
                    $ret = $this->replace($arr);
                } else if($time > $rexpires - $CFG['mysql_session_delay'] && $time < $rexpires) {
                    // 在到期与前某时间点之间
                    $ret = $this->replace($arr);
                } else {
                    // do not write;
                    $ret = ture;
                }
            } else {
                // do not write;
                $ret = ture;
            }
        } else if(! empty($_SESSION)){
            $ret = $this->replace($arr);
        }
        
        return empty($ret) ? false : true;
    }
    public function destroy($sessionId) {
        $ret = $this->del($sessionId);
        return empty($ret) ? false : true;
    }
    public function gc() {
        global $CFG;
        if(empty($CFG['cron_open'])) {
            $this->clean();
        } else {
            return true;
        }
    }

    /**
     * 清除过期授权码
     */
    public function clean() {
        $field1 = $this->model()->toField('expires');
        $ret = $this->model()->db()->delete($field1 . ' < UNIX_TIMESTAMP()');
        return empty($ret) ? false : true;
    }
}
// PHP END