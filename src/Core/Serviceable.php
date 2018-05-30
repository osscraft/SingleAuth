<?php
namespace Dcux\Core;

use Dcux\DB\CRUDable;
use Dcux\Core\Model;
use Dcux\DB\Cache;

interface Serviceable extends CRUDable {
    /**
     * 默认Model对象
     * @return Model
     */
    public function model();
}
// PHP END