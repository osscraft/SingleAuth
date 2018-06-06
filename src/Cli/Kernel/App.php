<?php

namespace Dcux\Cli\Kernel;

use Lay\Advance\Util\Logger;
use Lay\Advance\Http\Request;
use Lay\Advance\Core\Configuration;

class App extends \Lay\Advance\Core\App
{
    /**
     * App初始化
     *
     * @return void
     */
    public function initialize()
    {
        // 注册shutdown事件，更新类路径缓存
        // 运行在CLI下，默认不更新，此处设置强制更新
        register_shutdown_function(array(
                'Autoloader',
                'updateCache'
        ));
        // init config
        $this->initConfig();
        // init docpath
        $this->initDocpath();
        // init Logger
        $this->initLogger();
    }
    protected function initConfig()
    {
        $path = \Lay\Advance\Core\App::$_rootpath;
        $env = \Lay\Advance\Core\App::get('env', 'test');
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        if (file_exists($configfile)) {
            Configuration::configure($configfile);
            Configuration::loadCache();// reload config cache
        }
    }
    protected function initDocpath()
    {
        App::$_docpath = App::$_rootpath . DIRECTORY_SEPARATOR . 'cmd';
    }
    protected function initLogger()
    {
        Logger::getInstance()->directory(App::$_docpath);
    }
    // override detect classname
    protected function detect($webpath, $prefix = '\\Dcux\\Cli\\Action\\')
    {
        return parent::detect($webpath, $prefix);
    }
}
// PHP END
