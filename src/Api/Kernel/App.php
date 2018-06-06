<?php

namespace Dcux\Api\Kernel;

use Lay\Advance\Util\Logger;
use Lay\Advance\Http\Request;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Core\Error;
use Lay\Advance\Core\Errode;
use Exception;

class App extends \Lay\Advance\Core\App
{
    protected $trustee = '\Dcux\Api\Action\Error\Error';
    /**
     * App初始化
     *
     * @return void
     */
    public function initialize()
    {
        // init config
        $this->initConfig();

        self::$_event->listen(get_class(self::$_app), App::E_ERROR, array($this, 'log'));
    }
    // init particular config
    protected function initConfig()
    {
        $path = \Lay\Advance\Core\App::$_rootpath;
        $env = \Lay\Advance\Core\App::get('env', 'test');
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        if (file_exists($configfile)) {
            Configuration::configure($configfile);
            Configuration::loadCache();// reload config cache
        }
    }
    // override detect classname
    protected function detect($webpath, $prefix = '\\Dcux\\Api\\Action\\')
    {
        $wwwpath = realpath(App::$_docpath) . DIRECTORY_SEPARATOR .'api' . DIRECTORY_SEPARATOR;
        return parent::detect($wwwpath, $prefix);
    }
    // before run Action
    protected function before()
    {
        try {
            if (empty($this->classname) || !class_exists($this->classname)) {
                $this->classname = $this->trustee;
            } else {
                parent::before();
            }
        } catch (\Exception $err) {
            throw new Error(Errode::api_not_exists($this->apiname), 0, $err);
        }
    }
    protected function after()
    {
        $this->log();
        parent::after();
    }

    // record log
    public function log()
    {
        if (!empty(self::$_action)) {
            Logger::info(self::$_action->getLogInfo(), 'api');
        }
    }
}
// PHP END
