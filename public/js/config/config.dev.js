var config = {
    baseURL:'http://127.0.0.1:8800/',
    authorizeURL:'http://127.0.0.1:8800/authorize.php',
    accessTokenURL:'http://127.0.0.1:8800/token.php',
    logoutURL:'http://127.0.0.1:8800/logout.php',
    resourceURL:'http://127.0.0.1:8800/resource.php',
    isLoginURL:'http://127.0.0.1:8800/isLogin.php',
    keepLoginURL:'http://127.0.0.1:8800/keep.php',

    clientId:'ufsso_dcux_portal',
    clientSecret:'',
    redirectURI:'http://127.0.0.1:8800/index.html',

    ws_uri: 'ws://192.168.0.18:843',
    ws_expires: 60000,//60秒

    online_num: 360,
    online_interval: 10000,
    portal_app_has_title: true
};
var Config = config;