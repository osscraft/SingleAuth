<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;
use Lay\Advance\DB\DataBase;

class Setting extends Model {
    protected $k = '';
    protected $v = '';
    protected $info = '';
    public function cacher() {
        $cacher = Database::factory('configcacher');
        $cacher->setModel($this);
        return $cacher;
    }
    public function schema() {
        return 'sso';
    }
    public function table() {
        return 'setting';
    }
    public function primary() {
        return 'k';
    }
    public function columns() {
        return array (
                'k' => 'k',
                'v' => 'v', 
				'info' =>'info'
        );
    }
}
?>
