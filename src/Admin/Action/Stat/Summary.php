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

class Summary extends MenuPermission
{
    public function cmd()
    {
        return 'statistics.summary';
    }
    public function onCreate()
    {
        parent::onCreate();
        $t = $this->template->getTheme();
        if (empty($t) || $t == 'default') {
            //直接跳至
            $this->template->redirect('/admin/stat.php', array(), false);
        }
    }
    public function onGet()
    {
        $this->template->file('stat/summary.php');
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
