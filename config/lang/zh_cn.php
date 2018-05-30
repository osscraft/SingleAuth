<?php
/**
 * 语言包文件
 * @category  
 * @package   lang 
 * @author    liaiyong <liangjun@dcux.com>
 * @version   
 * @copyright 2005-2012 dcux Inc.
 * @link      http://www.dcux.com
 * 
 */
global $CFG;
$CFG['LANG']['REQUEST_ERROR'] = '无效请求';
$CFG['LANG']['INVALID_ACCESS_TOKEN'] = '无效令牌';
$CFG['LANG']['USER_OR_PASS_ERROR'] = '用户名或密码错误';
$CFG['LANG']['TITLE_SPLIT_SIGN'] = ' :: ';
$CFG['LANG']['TITLE_INDEX'] = '管理主页';
$CFG['LANG']['CLIENT_MANAGER'] = '客户端管理';
$CFG['LANG']['LDAP_CONFIG_MANAGER'] = 'LDAP配置管理';
$CFG['LANG']['SETTING_MANAGER'] = '配置管理';
$CFG['LANG']['STATISTICS'] = '统计';
$CFG['LANG']['USER_MANAGER'] = '用户管理';
$CFG['LANG']['DENIED_USER'] = '非法用户';
$CFG['LANG']['PLEASE'] = '请';
$CFG['LANG']['PLEASE_LOGOUT'] = '请<a href="logout.php"><font color="#FF0000">登出</font></a>';
$CFG['LANG']['VIEW'] = '查看';
$CFG['LANG']['CREATE'] = '创建';
$CFG['LANG']['CREATING'] = '创建中';
$CFG['LANG']['MODIFY'] = '更新';
$CFG['LANG']['MODIFING'] = '更新中';
$CFG['LANG']['DELETE'] = '删除';
$CFG['LANG']['DELETING'] = '删除中';
$CFG['LANG']['LIST'] = '列表';
$CFG['LANG']['ID'] = 'ID';
$CFG['LANG']['CLIENT_NAME'] = '客户端名称';
$CFG['LANG']['CLIENT_TYPE'] = '客户端类型';
$CFG['LANG']['CLIENT_DESCRIBE'] = '客户端描述';
$CFG['LANG']['CLIENT_ID'] = '客户端标识符';
$CFG['LANG']['CLIENT_SECRET'] = '客户端密钥';
$CFG['LANG']['REDIRECT_URI'] = '重定向地址';
$CFG['LANG']['CLIENT_SCOPE'] = '资源访问域';
$CFG['LANG']['CLIENT_LOCATION'] = '客户端地址';
$CFG['LANG']['CLIENT_LOGOURI'] = '客户端LOGO路径';
$CFG['LANG']['CLIENT_ISSHOW'] = '在首页显示';
$CFG['LANG']['CLIENT_VISIBLE'] = '可见性';
$CFG['LANG']['TOKEN_LIFETIME'] = '有效期';

$CFG['LANG']['OPERATE'] = '操作';
$CFG['LANG']['CODE'] = '授权码';
$CFG['LANG']['EXPIRES'] = '过期时间';
$CFG['LANG']['SCOPE'] = '授权范围';
$CFG['LANG']['OAUTH_TOKEN'] = '令牌码';
$CFG['LANG']['TOKEN'] = '令牌';
$CFG['LANG']['ACCESS_TOKEN'] = '访问令牌';
$CFG['LANG']['REFRESH_TOKEN'] = '刷新令牌';
$CFG['LANG']['USER_ID'] = '授权用户ID';
$CFG['LANG']['AUTH_TIME'] = '授权时间';
$CFG['LANG']['REMARK'] = '备注';
$CFG['LANG']['HOST'] = 'HOST';
$CFG['LANG']['BASE_DN'] = 'BASE DN';
$CFG['LANG']['RESOURCE_DN'] = 'RESOURCE DN';
$CFG['LANG']['ROOT_DN'] = 'ROOT DN';
$CFG['LANG']['ROOT_PW'] = 'ROOT PW';
$CFG['LANG']['UID'] = '用户ID';
$CFG['LANG']['USERNAME'] = '用户名';
$CFG['LANG']['IS_ADMIN'] = '是否管理员';
$CFG['LANG']['WELCOME'] = '欢迎进入SSO管理系统';
$CFG['LANG']['INDEX'] = '主页';
$CFG['LANG']['CLIENT_LIST'] = '客户端列表';
$CFG['LANG']['LDAP_CONFIG'] = 'LDAP配置';
$CFG['LANG']['AUTH_INFO_LIST'] = '授权信息列表';
$CFG['LANG']['USER_LIST'] = '管理用户';
$CFG['LANG']['REFRESH'] = '刷新';
$CFG['LANG']['LOGIN'] = '登录';
$CFG['LANG']['LOGOUT'] = '登出';
$CFG['LANG']['HELLO'] = '你好,';
$CFG['LANG']['HELLO_LEFT'] = '你好,';
$CFG['LANG']['HELLO_RIGHT'] = ',你好';
$CFG['LANG']['K'] = '键';
$CFG['LANG']['V'] = '值';
$CFG['LANG']['INFO'] = '配置项说明';
$CFG['LANG']['ERROR']['NOT_LOGGED_IN'] = 'not_logged_in';

$CFG['LANG']['OAUTH2_ERROR'] = array(
    'INVALID_REQUEST' => 'invalid_request',
    'INVALID_CLIENT' => 'invalid_client',
    'INVALID_GRANT' => 'invalid_grant',
    'INVALID_TOKEN' => 'invalid_token',
    'UNSUPPORTED_RESPONSE_TYPE' => 'unsupported_response_type',
    'UNSUPPORTED_GRANT_TYPE' => 'unsupported_grant_type'
);
$CFG['LANG']['OAUTH2_ERROR_CODE'] = array(
    'INVALID_REQUEST' => 12310,
    'INVALID_CLIENT' => 12311,
    'INVALID_GRANT' => 12312,
    'INVALID_TOKEN' => 12313,
    'UNSUPPORTED_RESPONSE_TYPE' => 12314,
    'UNSUPPORTED_GRANT_TYPE' => 12315
);
$CFG['LANG']['OAUTH2_ERROR_DESC'] = array(
    'INVALID_REQUEST' => '请求不合法',
    'INVALID_CLIENT' => '客户端无效',
    'INVALID_GRANT' => '提供的Access Grant是无效的、过期的或已撤销的',
    'INVALID_TOKEN' => '提供的Access Token是无效的、过期的或已撤销的',
    'UNSUPPORTED_RESPONSE_TYPE' => '不支持的 Response Type',
    'UNSUPPORTED_GRANT_TYPE' => '不支持的 Grant Type'
);

// common
$CFG['LANG']['CURRENT_YEAR'] = date('Y');
$CFG['LANG']['TEACHER'] = '教师';
$CFG['LANG']['STUDENT'] = '学生';
$CFG['LANG']['OTHER'] = '其他';
$CFG['LANG']['ACCOUNT'] = '帐号';
$CFG['LANG']['USERNAME'] = '用户名';
$CFG['LANG']['PASSWORD'] = '密码';
$CFG['LANG']['SIGNIN'] = '登录';
$CFG['LANG']['SIGNOUT'] = '退出';
$CFG['LANG']['SIGNUP'] = '注册';
$CFG['LANG']['FORGOT_PASSWORD'] = '忘记密码';
$CFG['LANG']['VERIFY_CODE'] = '验证码';
$CFG['LANG']['VERIFY_CODE_IMAGE'] = '点击换图';
$CFG['LANG']['OTHER_ACCOUNT'] = '其他帐号';
$CFG['LANG']['LOGIN_BY_YB'] = '易班登录';
$CFG['LANG']['HOMEPAGE'] = '首页';
$CFG['LANG']['MAINPAGE'] = '主页';
$CFG['LANG']['ABOUT'] = '关于';
$CFG['LANG']['CONTACT'] = '联系我们';
$CFG['LANG']['COMMA'] = '，';
$CFG['LANG']['COLON'] = '：';
$CFG['LANG']['DCUX'] = '上海龙盟信息科技有限公司';
$CFG['LANG']['DCUX_SUPPORT'] = '提供技术支持';
$CFG['LANG']['ALL_RIGHTS_RESERVED'] = 'All Rights Reserved';
$CFG['LANG']['PRIVACY_POLICY'] = 'Privacy Policy';

// portal
$CFG['LANG']['PORTAL_TITLE_OLD'] = '首页';
$CFG['LANG']['PORTAL_TITLE'] = '认证开放平台';
$CFG['LANG']['PORTAL_WELCOME'] = '欢迎使用龙盟认证开放平台';
$CFG['LANG']['PORTAL_LOGO'] = 'LOGO';
$CFG['LANG']['PORTAL_WIKI'] = '文档';
$CFG['LANG']['PORTAL_HELP'] = '帮助';
$CFG['LANG']['PORTAL_HELP_INFO'] = '帮助信息';
$CFG['LANG']['PORTAL_SUPPORT'] = '支持';
$CFG['LANG']['PORTAL_LOG'] = '日志';
$CFG['LANG']['PORTAL_LOG_TITLE'] = '用户登录日志报表';
$CFG['LANG']['PORTAL_LOG_REPORT'] = '日志报表';
$CFG['LANG']['PORTAL_LOG_VIEW'] = '查看日志';
$CFG['LANG']['PORTAL_ELECTION'] = '自定义';
$CFG['LANG']['PORTAL_STATISTICS'] = '统计信息';
$CFG['LANG']['PORTAL_STATISTICS_CLIENT_TOP'] = '应用排名';
$CFG['LANG']['PORTAL_STATISTICS_USER_TOP'] = '用户排名';
$CFG['LANG']['PORTAL_STATISTICS_ONLINE'] = '在线用户数';
$CFG['LANG']['PORTAL_STATISTICS_CLIENT_DATE'] = '访问次数';
$CFG['LANG']['PORTAL_SIGNIN'] = '登录';
$CFG['LANG']['PORTAL_SIGNIN_AND_GRANT'] = '登录并授权';
$CFG['LANG']['PORTAL_GRANT'] = '授权';
$CFG['LANG']['PORTAL_SIGNIN_GET'] = '获取身份认证';
$CFG['LANG']['PORTAL_SIGNOUT'] = '退出';
$CFG['LANG']['PORTAL_SIGNING'] = '登录中';
$CFG['LANG']['PORTAL_HAD_ACCOUNT'] = '系统检测到您正在使用帐号';
$CFG['LANG']['PORTAL_CLICK_TO_SIGNIN'] = '点击登录继续访问应用';
$CFG['LANG']['PORTAL_FILL_IN_YOUR_INFO'] = '请正确输入您的用户信息';
$CFG['LANG']['PORTAL_USE_APP'] = '以便访问应用';
$CFG['LANG']['PORTAL_SSO_SIGNIN'] = 'SSO登录';
$CFG['LANG']['PORTAL_TRANDITIONAL_SIGNIN'] = '传统登录';
$CFG['LANG']['PORTAL_HOMEPAGE'] = '首页';
$CFG['LANG']['PORTAL_MAINPAGE'] = '主页';
$CFG['LANG']['PORTAL_ABOUT'] = '关于';
$CFG['LANG']['PORTAL_CONTACT'] = '联系我们';
$CFG['LANG']['PORTAL_YOUR_NAME'] = '你的公司名称';
$CFG['LANG']['PORTAL_YOUR_ADDRESS'] = '你的公司地址';
$CFG['LANG']['PORTAL_DCUX'] = '上海龙盟信息科技有限公司';
$CFG['LANG']['PORTAL_DCUX_SUPPORT'] = '提供技术支持';
$CFG['LANG']['PORTAL_ICP'] = '你的ICP号';
$CFG['LANG']['PORTAL_START_YEAR'] = '2011';


/*$CFG['LANG']['ADMIN_TITLE'] = '认证开放平台管理系统';
$CFG['LANG']['ADMIN_WELCOME'] = '欢迎使用认证开放平台管理系统';*/
$CFG['LANG']['ADMIN_TITLE'] = '认证开放平台管理系统';
$CFG['LANG']['ADMIN_WELCOME'] = '欢迎使用认证开放平台管理系统';
$CFG['LANG']['ADMIN_WELCOME_EN'] = 'Welcome Open Authentication Platform Administration System!';
$CFG['LANG']['ADMIN_WELCOME_CN'] = '欢迎使用认证开放平台管理系统';
//$CFG['LANG']['ADMIN_WELCOME_CN'] = '欢迎使用认证平台管理系统';
$CFG['LANG']['ADMIN_YOUR_NAME'] = '你的公司名称';
$CFG['LANG']['ADMIN_YOUR_ADDRESS'] = '你的公司地址';
$CFG['LANG']['ADMIN_GRANT_MANAGE'] = '权限管理';
$CFG['LANG']['ADMIN_GRANT_CONFIGURE'] = '权限配置';
$CFG['LANG']['ADMIN_APP_MANAGE'] = '应用管理';
$CFG['LANG']['ADMIN_APP_LIST'] = '应用列表';
$CFG['LANG']['ADMIN_SETTING_MANAGE'] = '配置管理';
$CFG['LANG']['ADMIN_SETTING_LIST'] = '配置列表';
$CFG['LANG']['ADMIN_THEME_MANAGE'] = '皮肤管理';
$CFG['LANG']['ADMIN_THEME_LIST'] = '皮肤列表';
$CFG['LANG']['ADMIN_USER_MANAGE'] = '用户管理';
$CFG['LANG']['ADMIN_USER_LIST'] = '用户列表';
$CFG['LANG']['ADMIN_ADMIN_LIST'] = '管理员列表';
$CFG['LANG']['ADMIN_USER_GRANT_LIST'] = '授权列表';
$CFG['LANG']['ADMIN_STATISTICS_GRAPH'] = '统计图表';
$CFG['LANG']['ADMIN_STATISTICS_SUMMARY'] = '概览';
$CFG['LANG']['ADMIN_STATISTICS_VISIT'] = '访问';
$CFG['LANG']['ADMIN_STATISTICS_RANK'] = '排名';
$CFG['LANG']['ADMIN_STATISTICS_CLIENT_RANK'] = '应用排名';
$CFG['LANG']['ADMIN_STATISTICS_USER_RANK'] = '用户排名';
$CFG['LANG']['ADMIN_STATISTICS_SCATTERGRAM'] = '分布';
$CFG['LANG']['ADMIN_STATISTICS_RAW_LOG'] = '日志';


return $CFG;

// PHP END
