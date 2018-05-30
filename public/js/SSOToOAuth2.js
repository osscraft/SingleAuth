var SSOToOAuth2 = function(clientId, clientSecret, redirectURI, accessToken, refreshToken) {
   this.clientId     = clientId;
   this.clientSecret = clientSecret;
   this.redirectURI  = redirectURI;
   this.accessToken  = accessToken;
   this.refreshToken = refreshToken;
};
SSOToOAuth2.prototype.authorizeURL   = config.authorizeURL ? config.authorizeURL : 'http://sso.lixin.edu.cn/authorize.php';
SSOToOAuth2.prototype.accessTokenURL = config.accessTokenURL ? config.accessTokenURL : 'http://sso.lixin.edu.cn/token.php';
SSOToOAuth2.prototype.logoutURL      = config.logoutURL ? config.logoutURL : 'http://sso.lixin.edu.cn/logout.php';
SSOToOAuth2.prototype.resourceURL    = config.resourceURL ? config.resourceURL : 'http://sso.lixin.edu.cn/resource.php';
SSOToOAuth2.prototype.isLoginURL     = config.isLoginURL ? config.isLoginURL : 'http://sso.lixin.edu.cn/isLogin.php';
SSOToOAuth2.prototype.keepLoginURL    = config.keepLoginURL ? config.keepLoginURL : 'http://sso.lixin.edu.cn/keep.php';
SSOToOAuth2.prototype.getAuthorizeURL = function(url, response_type, state) {
    if(!url) { return; }
    var param = {};
    param.client_id = this.clientId;
    param.redirect_uri = url;
    param.response_type = (response_type)?response_type:'token';
    param.state = (state)?state:'qawsed';
    return this.authorizeURL + '?' + $.param(param);
};
SSOToOAuth2.prototype.getLogoutURL = function(url) {
    var param = {};
    param.access_token  = this.accessToken;
    param.response_type  = 'token';
    if(this.refreshToken) { param.refresh_token = this.refreshToken; }
    if(url) { param.redirect_uri  = url; }
    return this.logoutURL + '?' + $.param(param);
};
SSOToOAuth2.prototype.getUserInfo = function(fn) {
   $.post(this.resourceURL,{access_token:this.accessToken},fn,'json');
};
SSOToOAuth2.prototype.checkLogin = function(fn) {
   $.post(this.isLoginURL,{'client_id':this.clientId,'redirect_uri':this.redirectURI},fn,'json');
};
SSOToOAuth2.prototype.keepLogin = function(fn) {
   $.post(this.keepLoginURL,{},fn,'json');
}
