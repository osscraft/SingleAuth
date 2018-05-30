<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;

class StatOnline extends Model {
    protected $id = 0;
    protected $time = '';
    protected $count = 0;
    public function schema() {
        return 'sso';
    }
    public function table() {
        return 'stat_online';
    }
    public function primary() {
        return 'id';
    }
    public function columns() {
        return array (
                'id' => 'id',
                'time' => 'time',
                'count' => 'count'
        );
    }
}
// PHP END