<?php

namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;

class StatBrowser extends Model
{
    protected $id = 0;
    protected $browser = '';
    protected $version = '';
    protected $count = 0;
    public function schema()
    {
        return 'sso';
    }
    public function table()
    {
        return 'stat_browser';
    }
    public function primary()
    {
        return 'id';
    }
    public function columns()
    {
        return array(
                'id' => 'id',
                'browser' => 'browser',
                'version' => 'version',
                'count' => 'count'
        );
    }
}
