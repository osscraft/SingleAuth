<?php
namespace Lay\Advance\Core;

use Lay\Advance\DB\CRUDable;
use Lay\Advance\Core\Model;
use Lay\Advance\DB\Cache;

interface Serviceable extends CRUDable {
    /**
     * 默认Model对象
     * @return Model
     */
    public function model();
}
// PHP END