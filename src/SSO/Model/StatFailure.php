<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;

class StatFailure extends Model
{
    protected $id = 0;
    protected $date = '';
    protected $ip = 0;
    protected $clientId = '';
    protected $count = 0;
    public function schema()
    {
        return 'sso';
    }
    public function table()
    {
        return 'stat_failure';
    }
    public function primary()
    {
        return 'id';
    }
    public function columns()
    {
        return array(
                'id' => 'id',
                'date' => 'date',
                'ip' => 'ip',
                'clientId' => 'client_id',
                'count' => 'count'
        );
    }
}
