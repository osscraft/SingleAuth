var config = {
    baseURL:'http://sso.project.dcux.com/',
    authorizeURL:'http://sso.project.dcux.com/authorize.php',
    accessTokenURL:'http://sso.project.dcux.com/token.php',
    logoutURL:'http://sso.project.dcux.com/logout.php',
    resourceURL:'http://sso.project.dcux.com/resource.php',
    isLoginURL:'http://sso.project.dcux.com/isLogin.php',
    keepLoginURL:'http://sso.project.dcux.com/keep.php',

    clientId:'ufsso_dcux_portal',
    clientSecret:'',
    redirectURI:'http://sso.project.dcux.com/index.html',

    ws_uri: 'ws://192.168.0.18:843',
    ws_expires: 60000,//60秒

    online_num: 360,
    online_interval: 10000,
    portal_app_has_title: true
};
var Config = config;