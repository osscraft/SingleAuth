<?php

namespace Dcux\Cli\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\DB\DataBase;
use Lay\Advance\DB\Mysql;

class TransferSetting extends Model {
    protected $k = '';
    protected $v = '';
    protected $i = '';
    public function schema() {
        return 'transfer';
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
                'i' => 'i'
        );
    }
}
?>
