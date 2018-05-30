<?php
global $CFG;
$CFG['SSO_CLIENT_ID'] = 'ufsso_changepass_test';
$CFG['SSO_CLIENT_SECRET'] = '';
$CFG['SSO_CALLBACK'] = 'http://192.168.0.22:88/ChangePass/index.php';

return $CFG;
// PHP END