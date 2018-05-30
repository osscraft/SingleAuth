<?php

namespace Dcux\SSO\Kernel;

use Lay\Advance\Util\Logger;
use Lay\Advance\Http\Request;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Core\Error;

use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Core\MySession;

class App extends \Lay\Advance\Core\App {
    protected $trustee = '\Dcux\SSO\Action\Page\PError';
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
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'sso' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        if(file_exists($configfile)) {
            Configuration::configure($configfile);
            Configuration::loadCache();// reload config cache
        }
    }
}
// PHP END