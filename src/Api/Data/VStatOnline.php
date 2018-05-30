<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Model;

use Dcux\Api\Data\VObject;
use Dcux\SSO\Model\StatBrowser;
use stdClass;

class VStatOnline extends VObject {
	protected $id = 0;
    protected $time = '';
    protected $count = 0;
    public function rules() {
    	return array(
                'id' => Component::TYPE_INTEGER,
                'time' => Component::TYPE_DATETIME,
                'count' => Component::TYPE_INTEGER
    	);
    }
	public static function parseSimple($statOnline) {
        return parent::parse($statOnline);
    }
}
// PHP END