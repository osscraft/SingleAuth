<?php
global $CFG;
//SDK部分
$CFG['SSO_CLIENT_ID'] = 'sso_admin_client';
$CFG['SSO_CLIENT_SECRET'] = '9d4a75ff40757d45e51e1e60a07ed976';
$CFG['SSO_CLIENT_TYPE'] = 'webApp';
$CFG['SSO_REDIRECT_URI'] = 'http://127.0.0.1:8800/admin/index.php';
$CFG['SSO_CALLBACK'] = 'http://127.0.0.1:8800/admin/index.php';

/*$CFG['SSO_CLIENT_SECRET'] = '6141f731fbbf5c40fb5e64fc71393f88';//210.35.100.16
$CFG['mysql_host'] = '192.168.1.22';
$CFG['mysql_name'] = 'root';
$CFG['mysql_password'] = 'dcuxpasswd';
$CFG['mysql_database'] = 'sso';*/

$CFG['root_uid'] = 'administrator';//超级管理员用户ID
$CFG['root_username'] = 'administrator';//超级管理员用户名

// cover admin theme
//$CFG['theme']['admin'] = 'urban';
$CFG['theme_customize'] = false;

// lang
// include dirname(__DIR__) . '/lang/admin.' . $CFG['language'] . '.php';
// menu
include __DIR__ . '/menu.php';

return $CFG;
// PHP END
