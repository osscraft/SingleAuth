<?php

namespace Dcux\SSO\Kernel;

use Lay\Advance\Core\Configuration;

abstract class Action extends \Lay\Advance\Core\Action
{
    public function onCreate()
    {
        parent::onCreate();
        // init theme
        $this->initTheme();
    }
    protected function initTheme()
    {
        global $CFG;
        if (!empty($_COOKIE['theme_main']) && !empty($CFG['theme_customize'])) {
            $this->template->theme($_COOKIE['theme_main']);
        }
    }
}
// PHP END
