<?php
namespace Dcux\Cli\Service;

use Lay\Advance\Core\Service;

use Dcux\Cli\Model\TransferUsers;

class TransferUsersService extends Service {
	private $users;
    protected function __construct() {
        parent::__construct();
        $this->users = TransferUsers::getInstance();

    }
	public static function getInstance() {
        $instance = parent::getInstance();
		return $instance;
    }
    // base user 
	public function model(){
		return $this->users;
	}
}
// PHP END