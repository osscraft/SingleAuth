var C = {
};
C.dev = {
	authorizeURL:'http://sso.project.dcux.com/authorize.php',
	accessTokenURL:'http://sso.project.dcux.com/token.php',
	logoutURL:'http://sso.project.dcux.com/logout.php',
	resourceURL:'http://sso.project.dcux.com/resource.php',
	isLoginURL:'http://sso.project.dcux.com/isLogin.php',
	keepLoginURL:'http://sso.project.dcux.com/keep.php',

    clientId:'ufsso_dcux_portal',
    clientSecret:'',
    redirectURI:'http://sso.project.dcux.com/index.html'
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
C.lixin = {
	authorizeURL:'http://sso.lixin.edu.cn/authorize.php',
	accessTokenURL:'http://sso.lixin.edu.cn/token.php',
	logoutURL:'http://sso.lixin.edu.cn/logout.php',
	resourceURL:'http://sso.lixin.edu.cn/resource.php',
	isLoginURL:'http://sso.lixin.edu.cn/isLogin.php',
	keepLoginURL:'http://sso.lixin.edu.cn/keep.php',

    clientId:'ufsso_longmeng_portal_index',
    clientSecret:'',
    redirectURI:'http://sso.lixin.edu.cn/index.html'
};
C['p16.lixin'] = {
	authorizeURL:'http://210.35.100.16/authorize.php',
	accessTokenURL:'http://210.35.100.16/token.php',
	logoutURL:'http://210.35.100.16/logout.php',
	resourceURL:'http://210.35.100.16/resource.php',
	isLoginURL:'http://210.35.100.16/isLogin.php',
	keepLoginURL:'http://210.35.100.16/keep.php',

    clientId:'ufsso_longmeng_portal_index_16',
    clientSecret:'',
    redirectURI:'http://210.35.100.16/index.html'
};
C['pre.lixin'] = {
	authorizeURL:'http://pre.sso.lixin.edu.cn/authorize.php',
	accessTokenURL:'http://pre.sso.lixin.edu.cn/token.php',
	logoutURL:'http://pre.sso.lixin.edu.cn/logout.php',
	resourceURL:'http://pre.sso.lixin.edu.cn/resource.php',
	isLoginURL:'http://pre.sso.lixin.edu.cn/isLogin.php',
	keepLoginURL:'http://pre.sso.lixin.edu.cn/keep.php',

    clientId:'ufsso_longmeng_portal_index_pre',
    clientSecret:'',
    redirectURI:'http://pre.sso.lixin.edu.cn/index.html'
};

var config = C[env];
