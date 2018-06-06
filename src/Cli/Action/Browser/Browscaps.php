<?php

namespace Dcux\Cli\Action\Browser;

use Lay\Advance\Util\Browscap;

use Dcux\Cli\Kernel\CliAction;

class Browscaps extends CliAction
{
    protected $cacheDir;
    public function on()
    {
        $ret = true;
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $browscap = new Browscap();
        $shouldBeUpdated = $browscap->shouldCacheBeUpdated();
        if ($shouldBeUpdated) {
            $ret = $browscap->updateCache();
            if ($ret) {
                chmod($browscap->cacheDir . $browscap->cacheFilename, 0777);
                $this->template->push("code", 0);
                $this->template->push("data", "done update browserscap");
            } else {
                $this->template->push("code", 900003);
                $this->template->push("data", "error update browserscap");
            }
        } else {
            $this->template->push("code", 0);
            $this->template->push("data", "not update browserscap");
        }
    }
}
