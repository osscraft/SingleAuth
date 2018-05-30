<?php
global $CFG;

// client error:50****
// oauth2 error:20****
// user error:40****
// setting error:30****
// code error:10****
// api error: 80****

// ##--**
// 错误码及关键字
// coding error

$CFG['error'][404] = 'file_not_found';
$CFG['error'][500] = 'internal_server_error';

$CFG['error'][100000] = 'class_not_found';
$CFG['error'][100001] = 'invalid_classname';
$CFG['error'][100002] = 'invalid_action_class';

// oauth2
$CFG['error'][200000] = 'no_permission';
$CFG['error'][200001] = 'not_logged_in';
$CFG['error'][200100] = 'invalid_request';
$CFG['error'][200101] = 'invalid_client';
$CFG['error'][200102] = 'invalid_username_password';
$CFG['error'][200103] = 'invalid_user';
$CFG['error'][200104] = 'invalid_verify_code';
$CFG['error'][200105] = 'invalid_token';
$CFG['error'][200106] = 'invalid_grant';
// user
$CFG['error'][300000] = 'user_not_exists';
$CFG['error'][300001] = 'empty_user_id';
$CFG['error'][300002] = 'empty_userid';
$CFG['error'][300003] = 'unkown_user_role';
$CFG['error'][300100] = 'invalid_sid';
//client
$CFG['error'][310000] = 'client_not_exists';
$CFG['error'][310001] = 'empty_client_id';
$CFG['error'][310002] = 'empty_clientid';
//socket
$CFG['error'][600000] = 'invalid_socket';
$CFG['error'][600001] = 'invalid_scode';
$CFG['error'][600002] = 'invalid_sscode';

// api
$CFG['error'][800404] = 'api_not_exists';
$CFG['error'][800500] = 'app_server_error';
// common
$CFG['error'][900000] = 'invalid_param';
// unkown error
$CFG['error'][999999] = 'unkown_error';

// error flip
$CFG['error_flip'] = array_flip($CFG['error']);

// if have some other vars
$CFG['error'][100000] = 'class_not_found:%s';
$CFG['error'][100001] = 'invalid_classname:%s';
$CFG['error'][800404] = 'api_not_exists:%s';
$CFG['error'][900000] = 'invalid_param:%s';

return $CFG;
// PHP END