var config = {
    baseURL:'http://sso.dcux.com/',
    authorizeURL:'http://sso.dcux.com/authorize.php',
    accessTokenURL:'http://sso.dcux.com/token.php',
    logoutURL:'http://sso.dcux.com/logout.php',
    resourceURL:'http://sso.dcux.com/resource.php',
    isLoginURL:'http://sso.dcux.com/isLogin.php',
    keepLoginURL:'http://sso.dcux.com/keep.php',

    clientId:'ufsso_dcux',
    clientSecret:'',
    redirectURI:'http://sso.dcux.com/index.html',

    ws_uri: 'ws://sso.dcux.com:843',
    ws_expires: 60000,//60秒

    online_num: 360,
    online_interval: 10000,
    portal_app_has_title: true
};
var Config = config;