var config = {
    baseURL:'http://192.168.0.22:88/',
    authorizeURL:'http://192.168.0.22:88/authorize.php',
    accessTokenURL:'http://192.168.0.22:88/token.php',
    logoutURL:'http://192.168.0.22:88/logout.php',
    resourceURL:'http://192.168.0.22:88/resource.php',
    isLoginURL:'http://192.168.0.22:88/isLogin.php',
    keepLoginURL:'http://192.168.0.22:88/keep.php',

    clientId:'ufsso_dcux_portal_22',
    clientSecret:'',
    redirectURI:'http://192.168.0.22:88/index.html',

    ws_uri: 'ws://192.168.0.22:843',
    ws_expires: 60000,//60秒

    online_num: 360,
    online_interval: 10000,
    portal_app_has_title: true
};
var Config = config;