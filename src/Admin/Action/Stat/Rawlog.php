<?php

namespace Dcux\Admin\Action\Stat;

use Autoloader;
use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Paging;

use Dcux\Admin\Kernel\MenuPermission;
use Dcux\SSO\Service\StatUserDetailService;
use Dcux\SSO\Service\ClientService;

class Rawlog extends MenuPermission
{
    public function cmd()
    {
        return 'statistics.rawlog';
    }
    public function onGet()
    {
        $this->template->file('stat/rawlog.php');
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
