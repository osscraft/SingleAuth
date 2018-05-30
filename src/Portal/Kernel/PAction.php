<?php

namespace Dcux\Portal\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Dcux\SSO\Kernel\SAction;

abstract class PAction extends SAction {
    // overide
    protected function initTheme() {
        global $CFG;
        // init template dir
        $this->template->directory(\Lay\Advance\Core\App::$_docpath . DIRECTORY_SEPARATOR . 'portal');
        $this->template->theme(empty($CFG['theme']['portal']) ? 'default' : $CFG['theme']['portal']);
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