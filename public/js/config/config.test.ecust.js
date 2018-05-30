var config = {
    baseURL:'http://192.168.0.18:88/',
    authorizeURL:'http://192.168.0.18:88/authorize.php',
    accessTokenURL:'http://192.168.0.18:88/token.php',
    logoutURL:'http://192.168.0.18:88/logout.php',
    resourceURL:'http://192.168.0.18:88/resource.php',
    isLoginURL:'http://192.168.0.18:88/isLogin.php',
    keepLoginURL:'http://192.168.0.18:88/keep.php',

    clientId:'ufsso_dcux_portal_ecust',
    clientSecret:'',
    redirectURI:'http://192.168.0.18:88/index.html',

    ws_uri: 'ws://192.168.0.18:843',
    ws_expires: 60000,//60秒

    online_num: 60,
    online_interval: 60000,
    portal_app_has_title: true
};
var Config = config;