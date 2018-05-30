<?php

namespace Dcux\Admin\Kernel;

use Lay\Advance\Util\Logger;
use Lay\Advance\Http\Request;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Core\Error;
use Lay\Advance\Core\Errode;

use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Core\MySession;
use Exception;

class App extends \Lay\Advance\Core\App {
    protected $trustee = '\Dcux\Admin\Action\Page\PError';
    /**
     * App初始化
     * 
     * @return void
     */
    public function initialize() {
        // init config
        $this->initConfig();
    }
    // init particular config
    protected function initConfig() {
        $path = \Lay\Advance\Core\App::$_rootpath;
        $env = \Lay\Advance\Core\App::get('env', 'test');
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        if(file_exists($configfile)) {
            Configuration::configure($configfile);
            Configuration::loadCache();// reload config cache
        }
    }
    // override detect classname
    protected function detect($webpath, $prefix = '\\Dcux\\Admin\\Action\\') {
        $wwwpath = realpath(App::$_docpath) . DIRECTORY_SEPARATOR .'admin' . DIRECTORY_SEPARATOR;
        return parent::detect($wwwpath, $prefix);
    }
}
// PHP END