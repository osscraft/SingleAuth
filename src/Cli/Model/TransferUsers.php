<?php

namespace Dcux\Cli\Model;

use Lay\Advance\Core\Model;
use Lay\Advance\DB\DataBase;
use Lay\Advance\DB\Mysql;

class TransferUsers extends Model
{
    protected $userid = '';
    protected $password = '';
    protected $role = '';
    protected $name = '';
    protected $create_time = '';
    protected $status = 0;
    public function schema()
    {
        return 'transfer';
    }
    public function table()
    {
        return 'users';
    }
    public function primary()
    {
        return 'userid';
    }
    public function columns()
    {
        return array(
                'userid' => 'userid',
                'password' => 'password',
                'role' => 'role',
                'name' => 'name',
                'create_time' => 'create_time',
                'status' => 'status'
        );
    }
}
