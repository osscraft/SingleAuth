<?php

namespace Dcux\Portal\Kernel;

use Lay\Advance\Util\Logger;
use Lay\Advance\Http\Request;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Core\MySession;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Core\Error;
use Exception;

class App extends \Lay\Advance\Core\App {
    protected $trustee = '\Dcux\Portal\Action\Page\PError';
    /**
     * App初始化
     * 
     * @return void
     */
    public function initialize() {
        // init config
        $this->initConfig();
    }
    protected function initConfig() {
        $path = \Lay\Advance\Core\App::$_rootpath;
        $env = \Lay\Advance\Core\App::get('env', 'test');
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'portal' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        if(file_exists($configfile)) {
            Configuration::configure($configfile);
            Configuration::loadCache();// reload config cache
        }
    }
    // override detect classname
    protected function detect($webpath, $prefix = '\\Dcux\\Portal\\Action\\') {
        $wwwpath = realpath(App::$_docpath) . DIRECTORY_SEPARATOR .'portal' . DIRECTORY_SEPARATOR;
        return parent::detect($wwwpath, $prefix);
    }
}
// PHP END