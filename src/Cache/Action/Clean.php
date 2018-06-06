<?php

namespace Dcux\Cache\Action;

use Autoloader;

use Lay\Advance\Core\Configuration;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

class Clean extends CAction
{
    public function onGet()
    {
        $this->template->push('isok', true);
        // 前端缓存
        $this->cleanCache();
        // 配置信息缓存
        Configuration::cleanCache();
        // 类加载路径缓存
        Autoloader::cleanCache();
    }
}
// PHP END
