<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Utility;

use Dcux\SSO\Model\UserGrant;

class UserGrantService extends Service{
	private $userGrant;
	public function model(){
		if(empty($this->userGrant)) {
			$this->userGrant = UserGrant::getInstance();
		}
		return $this->userGrant;
	}
}
// PHP END