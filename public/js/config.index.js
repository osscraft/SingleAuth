var C = {
};
C.dev = {
	authorizeURL:'http://127.0.0.1:8800/authorize.php',
	accessTokenURL:'http://127.0.0.1:8800/token.php',
	logoutURL:'http://127.0.0.1:8800/logout.php',
	resourceURL:'http://127.0.0.1:8800/resource.php',
	isLoginURL:'http://127.0.0.1:8800/isLogin.php',
	keepLoginURL:'http://127.0.0.1:8800/keep.php',

    clientId:'ufsso_dcux_portal',
    clientSecret:'',
    redirectURI:'http://127.0.0.1:8800/index.html'
};
C.test = {
	authorizeURL:'http://192.168.0.22:88/authorize.php',
	accessTokenURL:'http://192.168.0.22:88/token.php',
	logoutURL:'http://192.168.0.22:88/logout.php',
	resourceURL:'http://192.168.0.22:88/resource.php',
	isLoginURL:'http://192.168.0.22:88/isLogin.php',
	keepLoginURL:'http://192.168.0.22:88/keep.php',

    clientId:'ufsso_dcux_portal_22',
    clientSecret:'',
    redirectURI:'http://192.168.0.22:88/index.html'
};
C.old = {
	authorizeURL:'http://192.168.0.22:8101/authorize.php',
	accessTokenURL:'http://192.168.0.22:8101/token.php',
	logoutURL:'http://192.168.0.22:8101/logout.php',
	resourceURL:'http://192.168.0.22:8101/resource.php',
	isLoginURL:'http://192.168.0.22:8101/isLogin.php',
	keepLoginURL:'http://192.168.0.22:8101/keep.php',

    clientId:'ufsso_dcux_portal_old',
    clientSecret:'',
    redirectURI:'http://192.168.0.22:8101/index.html'
};

var config = C[env];
