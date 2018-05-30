<?php

namespace Dcux\Dev\Kernel;

use Lay\Advance\Core\Configuration;

use Dcux\SSO\Kernel\Action;
use Dcux\SSO\Kernel\SAction;

abstract class DAction extends Action {
    // override
    protected function initTheme() {
        global $CFG;
        // init template dir
        $this->template->directory(\Lay\Advance\Core\App::$_docpath . DIRECTORY_SEPARATOR . 'dev');
        $this->template->theme(empty($CFG['theme']['dev']) ? 'default' : $CFG['theme']['dev']);
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