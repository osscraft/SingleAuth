<?php
// timezone
date_default_timezone_set('Asia/Shanghai');
// require autoload
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
// register classpath
// Autoloader::register(dirname(dirname(__DIR__)) . '/class');
// start app
Dcux\SSO\Kernel\App::start(dirname(dirname(__DIR__)), dirname(__DIR__), 'Dcux\SSO\Service\SettingService');

// PHP END