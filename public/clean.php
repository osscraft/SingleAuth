<?php
//require_once __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'bootstrap.php';

// PHP END

//clean by raw
$tmpdir = sys_get_temp_dir();
$calssfile = $tmpdir . '/sso.classes.php';
$configfile = $tmpdir . '/sso.config.php';

$ret = array();
$ret['class'] = @unlink($calssfile);
$ret['config'] = @unlink($configfile);

echo json_encode($ret);
