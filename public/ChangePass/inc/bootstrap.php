<?php
// timezone
date_default_timezone_set('Asia/Shanghai');
// require autoload
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/PHPLib/Autoloader.php';
// register classpath
Autoloader::register(dirname(dirname(dirname(__DIR__))) . '/class');
// start app
Dcux\ChangePass\Kernel\App::start(dirname(dirname(dirname(__DIR__))), dirname(dirname(__DIR__)), 'Dcux\SSO\Service\SettingService');

// PHP END