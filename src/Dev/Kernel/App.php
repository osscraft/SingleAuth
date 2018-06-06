<?php

namespace Dcux\Dev\Kernel;

use Lay\Advance\Util\Logger;
use Lay\Advance\Http\Request;
use Lay\Advance\Core\Configuration;

use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Core\MySession;

class App extends \Lay\Advance\Core\App
{
    /**
     * App初始化
     *
     * @return void
     */
    public function initialize()
    {
        // init config
        $this->initConfig();
    }
    protected function initConfig()
    {
        $path = \Lay\Advance\Core\App::$_rootpath;
        $env = \Lay\Advance\Core\App::get('env', 'test');
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dev' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        Configuration::configure($configfile);
        Configuration::loadCache();// reload config cache
    }
    // override detect classname
    protected function detect($webpath, $prefix = '\\Dcux\\Dev\\Action\\')
    {
        $wwwpath = realpath(App::$_docpath) . DIRECTORY_SEPARATOR .'dev' . DIRECTORY_SEPARATOR;
        return parent::detect($wwwpath, $prefix);
    }
    protected function before()
    {
        try {
            if (!class_exists($this->classname)) {
                $this->classname = '\Dcux\Dev\Action\Page\P404';
            }
        } catch (\Exception $e) {
            //
            $this->classname = '\Dcux\Dev\Action\Page\P404';
        }
        parent::before();
    }
    /**
     * 运行异常
     */
    protected function error($err)
    {
        parent::error($err);
        $this->classname = '\Dcux\Dev\Action\Page\P500';
        // restart life
        self::$_app->lifecycle();
    }
}
// PHP END
