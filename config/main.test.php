<?php
/**
 * oauth配置选项 
 * @category  
 * @package   
 * @author    liaiyong <liaiyong@dcux.com>
 * @version   1.0 
 * @copyright 2005-2015 dcux Inc.
 * @link      http://www.dcux.com
 * 
 */
global $CFG;
//SDK部分
$CFG['SSO_CLIENT_ID'] = 'ufsso_dcux_portal_22';
$CFG['SSO_CLIENT_SECRET'] = '';
$CFG['SSO_CLIENT_TYPE'] = 'jsApp';
$CFG['SSO_REDIRECT_URI'] = 'http://192.168.0.22:88/index.html';
$CFG['SSO_CALLBACK'] = 'http://192.168.0.22:88/index.html';
$CFG['SSO_AUTHORIZE_URL'] = 'http://192.168.0.22:88/authorize.php';
$CFG['SSO_TOKEN_URL'] = 'http://192.168.0.22:88/token.php';
$CFG['SSO_LOGOUT_URL'] = 'http://192.168.0.22:88/logout.php';
$CFG['SSO_RESOURCE_URL'] = 'http://192.168.0.22:88/resource.php';

//SSO项目地址
$CFG['project_path'] = 'http://192.168.0.22:88/';

//选择旧版LDAP数据服务器数据
$CFG['ldap_resource_old'] = false;
// 1.3 是否以syslog的方式记录日志
$CFG['log_by_syslog'] = false;

// 1.3 some url, or file path
$CFG['logo_main_url'] = '/images/logo.dcux.png';
$CFG['logo_dev_url'] = '/images/logo.dcux.png';
$CFG['logo_portal_url'] = '/images/logo.dcux.png';
$CFG['logo_admin_url'] = '/images/logo.dcux.png';
$CFG['forgot_password_url'] = 'javascript:;';
$CFG['portal_app_has_title'] = true;

//mysql config
$CFG['mysql_host'] = '192.168.0.22';
$CFG['mysql_name'] = 'root';
$CFG['mysql_password'] = 'dcuxpasswd';
$CFG['mysql_database'] = 'sso';
$CFG['mysql_showsql'] = true;

// 1.2 mysql config
$CFG['mysql']['default']['host'] = $CFG['mysql_host'];
$CFG['mysql']['default']['port'] = $CFG['mysql_port'];
$CFG['mysql']['default']['name'] = $CFG['mysql_name'];
$CFG['mysql']['default']['password'] = $CFG['mysql_password'];
$CFG['mysql']['default']['database'] = $CFG['mysql_database'];
$CFG['mysql']['default']['showsql'] = $CFG['mysql_showsql'];
$CFG['mysql']['session']['host'] = $CFG['mysql_host'];//session in mysql
$CFG['mysql']['session']['port'] = $CFG['mysql_port'];
$CFG['mysql']['session']['name'] = $CFG['mysql_name'];
$CFG['mysql']['session']['password'] = $CFG['mysql_password'];
$CFG['mysql']['session']['database'] = $CFG['mysql_database'];
$CFG['mysql']['session']['showsql'] = $CFG['mysql_showsql'];
// 1.2 mysql session
$CFG['mysql_session_keep'] = true;// 保持时 mysql_session_delay 设置有效
$CFG['mysql_session_lifetime'] = 1800;//设置memcache的过期时间
$CFG['mysql_session_delay'] = 60;//延迟写入时长,当离过期时间小于本值时更新，0为实时保持且不使用memcache缓存
// 1.2 memcache compatible
$CFG['memcache_compatible_date'] = '2015-6-30';//兼容旧版存在Memcache中的的会话
//1.2 memcache部分 for compatible 
$CFG['use_memcache'] = true;//是否启用memcache
$CFG['memcache_lifetime'] = $CFG['mysql_session_lifetime'];//设置memcache的过期时间
//$CFG['memcache_count'] = 1;//设置memcache服务器的数量
$CFG['memcaches'][0]['host'] = '192.168.0.22';//设置某memcache服务器的地址
$CFG['memcaches'][0]['port'] = 11211;//设置某memcache服务器的端口
//$CFG['memcaches'][1]['host'] = '192.168.0.23';//设置某memcache服务器的地址a
//$CFG['memcaches'][1]['port'] = 11211;//设置某memcache服务器的端口
// 1.2 memcache config
$CFG['memcache_host'] = '192.168.0.22';
$CFG['memcache_port'] = 11211;
$CFG['memcache_show'] = true;
$CFG['memcache']['default']['host'] = $CFG['memcache_host'];
$CFG['memcache']['default']['port'] = $CFG['memcache_port'];
$CFG['memcache']['default']['show'] = $CFG['memcache_show'];
// 1.2 ldap config
$CFG['ldap_host'] = '192.168.0.22';
$CFG['ldap_port'] = 389;
$CFG['ldap_name'] = 'cn=admin,dc=ldap,dc=lixin,dc=edu,dc=cn';
$CFG['ldap_pass'] = 'dcuxpasswd';
$CFG['ldap_base'] = 'o=sso,dc=ldap,dc=lixin,dc=edu,dc=cn';
$CFG['ldap_show'] = true;
$CFG['ldap']['default']['host'] = $CFG['ldap_host'];
$CFG['ldap']['default']['port'] = $CFG['ldap_port'];
$CFG['ldap']['default']['name'] = $CFG['ldap_name'];
$CFG['ldap']['default']['pass'] = $CFG['ldap_pass'];
$CFG['ldap']['default']['base'] = $CFG['ldap_base'];
$CFG['ldap']['default']['show'] = $CFG['ldap_show'];
// cron open
$CFG['cron_open'] = true;
// 1.2 identify database
$CFG['identify_database'] = 'ldap';

//选择语言包
$CFG['language'] = 'zh_cn';
// 1.2 themes
$CFG['themes']['main'] = array('default', 'imitate', 'detail', 'fix', 'urban', 'detailx');
$CFG['themes']['admin'] = array('default','urban');
$CFG['themes']['portal'] = array('default');
$CFG['themes']['dev'] = array('default');
$CFG['theme']['main'] = 'default';
$CFG['theme_customize'] = true;
// 1.2 log
$CFG['logo']['main'] = '/images/logo.dcux.png';
$CFG['logo']['dev'] = '/images/logo.dcux.png';
$CFG['logo']['portal'] = '/images/logo.dcux.png';
$CFG['logo']['admin'] = '/images/logo.dcux.png';

//oauth2标记量部分
$CFG['grant_type'][0] = 'authorization_code';
$CFG['grant_type'][1] = 'password';
$CFG['grant_type'][2] = 'refresh_token';
$CFG['client_type'][0] = 'webApp';
$CFG['client_type'][1] = 'desktopApp';
$CFG['client_type'][2] = 'jsApp';
$CFG['client_type'][3] = 'mobileApp';
$CFG['request_type'][0] = 'code';
$CFG['request_type'][1] = 'token';
$CFG['request_type'][2] = 'password';
$CFG['request_type'][3] = 'refresh_token';
$CFG['response_type'][0] = 'code';
$CFG['response_type'][1] = 'token';

//token部分
$CFG['use_refresh_token'] = false;//是否启用refresh_token
$CFG['auth_code_lifetime'] = 30;//设置验证码的过期时间
$CFG['access_token_lifetime'] = 1800;//设置access_token的过期时间
$CFG['refresh_token_lifetime'] = 86400;//60*60*24;//设置refresh_token的过期时间
$CFG['access_token_type'] = 0;//设置access_token的标记量
$CFG['refresh_token_type'] = 1;//设置refresh_token的标记量

// client js,css cache
$CFG['frontcache']['open'] = false;
$CFG['frontcache']['minimize'] = false;
$CFG['frontcache']['js']['open'] = $CFG['frontcache']['open'];
$CFG['frontcache']['js']['minimize'] = $CFG['frontcache']['minimize'];
$CFG['frontcache']['css']['open'] = $CFG['frontcache']['open'];
$CFG['frontcache']['css']['minimize'] = $CFG['frontcache']['minimize'];

// 1.2 unalterable keys
$CFG['unalterable_keys'] = array(
	// oauth2
	'grant_type', 'client_type', 'request_type', 'response_type', 'access_token_type', 'refresh_token_type',
	// main
	'SSO_CLIENT_ID', 'SSO_CLIENT_SECRET', 'SSO_CLIENT_TYPE', 'SSO_REDIRECT_URI', 'SSO_CALLBACK', 'SSO_AUTHORIZE_URL', 'SSO_TOKEN_URL', 'SSO_LOGOUT_URL', 'SSO_RESOURCE_URL', 'project_path',
	// 
	'unalterable_keys'
);
$CFG['unalterable_dynamic_keys'] = array();

// 接入第三方认证平台
$CFG['connection']['weibo']['app_key'] = '4154598188';
$CFG['connection']['weibo']['app_secret'] = 'ad688d7c4d72e429a8437e0ef2624757';

// 启用使用其他帐号登录功能
$CFG['OTHER_ACCOUNT'] = true;
//跳过登录确认页
$CFG['skip_if_has_authorized'] = true;// 用户对某应用已经授权，跳过登录确认页
$CFG['skip_if_has_logined'] = false;// 用户已经登录了，跳过登录确认页
$CFG['skip_delay'] = 5000;//延迟跳过毫秒数
//路径部分
//$CFG['path'] = __DIR__ ;//执行文件相对本文件所在目录的路径
//$CFG['classes_path'] = __DIR__ . '/classes/';//类文件目录相对本文件的路径
//$CFG['sdk_path'] = __DIR__ . '/SDK/';//SDK文件目录相对本文件的路径

// server config
$CFG['server']['gateway']['uri'] = 'Websocket://0.0.0.0:843';
$CFG['server']['gateway']['name'] = 'MyGateway';
$CFG['server']['gateway']['count'] = 4;
$CFG['server']['gateway']['start_port'] = 2000;
$CFG['server']['gateway']['ping_interval'] = 10;
$CFG['server']['gateway']['ping_data'] = '{"type":"ping"}';
$CFG['server']['business_worker']['name'] = 'MyusinessWorker';
$CFG['server']['business_worker']['count'] = 4;
$CFG['server']['internal_gateway']['uri'] = 'Text://0.0.0.0:7273';
$CFG['server']['internal_gateway']['name'] = 'MyInternalGateway';
$CFG['server']['internal_gateway']['start_port'] = 2800;
// qr code
$CFG['qr_code_lifetime'] = 300;// 300秒过期时间
// sid key
$CFG['sid_encrypt_key'] = 'key_for_sid';
// server key
$CFG['server_qrlogin_key'] = '123';
//$CFG['server_qrlogin_expires'] = 300;// 30秒过期时间
$CFG['server_internal_gateway'] = 'tcp://192.168.0.22:7273';// 内部服务地址及端口
// sso key
$CFG['sso_qrlogin_key'] = '456';
$CFG['sso_qrlogin_url'] = 'http://192.168.0.22:88/qrscan.php';

// mobile clients
$CFG['super_client_id'] = array("AndroidSDK");
// 语言包
include __DIR__ . '/lang/' . $CFG['language'] . '.php';
// error
include __DIR__ . '/error/error.php';//defines
include __DIR__ . '/error/error.' . $CFG['language'] . '.php';//languages

//兼容，返回数组
return $CFG;
// PHP END
