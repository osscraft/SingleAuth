<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Utility;

use Dcux\SSO\Model\UserElection;

class UserElectionService extends Service{
	private $userElection;
	public function model(){
		if(empty($this->userElection)) {
			$this->userElection = UserElection::getInstance();
		}
		return $this->userElection;
	}
    public function getUserElectionListAllByUser($uid) {
    	$condition = array();
    	$condition['uid'] = $uid;
    	$order = array();
    	$order['id'] = 'DESC';
    	$ret = $this->model()->db()->select(array(), $condition, $order, array(), false);
    	return empty($ret) ? array() : $ret;
    }
}
// PHP END