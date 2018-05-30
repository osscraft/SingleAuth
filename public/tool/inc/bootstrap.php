<?php
// timezone
date_default_timezone_set('Asia/Shanghai');
// require autoload
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/PHPLib/Autoloader.php';
// register classpath
Autoloader::register(dirname(dirname(dirname(__DIR__))) . '/class');
// start app
Dcux\Tool\Kernel\ToolApp::start(dirname(dirname(dirname(__DIR__))), dirname(dirname(__DIR__)), 'Dcux\SSO\Service\SettingService');

// PHP END