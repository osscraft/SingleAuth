<?php
namespace Dcux\Api\Data;

use Lay\Advance\Core\Component;

use Dcux\Api\Data\VBasic;
use Dcux\Api\Util\DataPicker;

class VObject extends VBasic
{
    protected static $datapicker;
    public function __construct()
    {
        parent::__construct();
        if (empty(VObject::$datapicker)) {
            VObject::$datapicker = new DataPicker();
        }
    }
}
// PHP END
