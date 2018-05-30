<?php

namespace Dcux\SSO\Kernel;

use Dcux\Core\Action;
use Dcux\Core\Configuration;
use Dcux\SSO\Kernel\SAction;

abstract class PAction extends SAction {
    public function onCreate() {
        global $CFG;
        // init config
        $this->initConfig();
        // init template dir
        $this->template->directory(\Dcux\Core\App::$_docpath . DIRECTORY_SEPARATOR . 'portal');
        $this->template->theme(empty($CFG['theme']['portal']) ? 'default' : $CFG['theme']['portal']);
        parent::onCreate();
    }
    protected function initConfig() {
        $path = \Dcux\Core\App::$_rootpath;
        $env = \Dcux\Core\App::get('env', 'test');
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'portal' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        Configuration::configure($configfile);
    }
    protected function errorResponse($error, $error_description = null, $error_uri = null) {
        global $CFG;
        $result['error'] = $error;
        
        if (! empty($CFG['display_error']) && $error_description)
            $result["error_description"] = $error_description;
        
        if (! empty($CFG['display_error']) && $error_uri)
            $result["error_uri"] = $error_uri;
        
        $this->template->push($result);
    }
}