<?php

namespace Dcux\SSO\Action;

use Dcux\Core\App;
use Dcux\SSO\Kernel\CAction;
use Dcux\Autoloader;
use Dcux\Core\Configuration;

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
