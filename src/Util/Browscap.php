<?php
namespace Dcux\Util;

class Browscap extends \phpbrowscap\Browscap
{
    public $doAutoUpdate = false;
    public $remoteIniUrl = 'http://browscap.org/stream?q=Full_PHP_BrowsCapINI';
    public function __construct($cache_dir = '')
    {
        global $CFG;
        parent::__construct($CFG['browscap_cachedir']);
    }
}

// PHP END
