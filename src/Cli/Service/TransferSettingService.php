<?php
namespace Dcux\Cli\Service;

use Lay\Advance\Core\Service;

use Dcux\Cli\Model\TransferSetting;

class TransferSettingService extends Service {
	private $setting;
    protected function __construct() {
        parent::__construct();
        $this->setting = TransferSetting::getInstance();

    }
	public static function getInstance() {
        $instance = parent::getInstance();
		return $instance;
    }
    // base user 
	public function model(){
		return $this->setting;
	}
}
// PHP END