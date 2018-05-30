
var Portal = {};
var OAuth2 = new SSOToOAuth2(config.clientId,config.clientSecret,config.redirectURI);

$(document).ready(function(){
    $(window).hashchange(function() {
        var hash = document.location.hash.substring(1);
        if(hash.substring(0,1) == '!') {
           hash = hash.substring(1);
        }
    });
    $(window).unload(function() {
        Portal.cleanToken();
        Portal.cleanUser();
    });
    $("#ssologout_btn a").click(function() {
        Portal.unhandRedirect(OAuth2.getLogoutURL(config.redirectURI));
    });

    var token = $.cookie("ufsso_longmeng_portal_token");
    var uid = $.cookie("ufsso_longmeng_portal_uid");
    var useranme = $.cookie("ufsso_longmeng_portal_useranme");
    var role = $.cookie("ufsso_longmeng_portal_role");
    var refreshToken = $.cookie("ufsso_longmeng_portal_refresh_token");
    var hash = document.location.hash;

    if(hash){
        hash      = hash.substring(1)
        var param = $.deparam(hash);
        if(param.access_token) {

            if(!token) {
                var date = new Date();
                param.expires_in = parseInt(param.expires_in,10);
                date.setTime(date.getTime() + (param.expires_in * 1000));
                OAuth2.accessToken  = param.access_token;
                OAuth2.refreshToken = param.refresh_token;
                Portal.saveToken(param, date);
            }
            if(!uid) {
                Portal.isloging();
                OAuth2.getUserInfo(function(json) {
                    if(json.error) {
                        Portal.redirect(OAuth2.getAuthorizeURL(config.redirectURI));
                    } else {
                        var date = new Date();
                        var expires_in = 259000;
                        date.setTime(date.getTime() + (expires_in * 1000));
                        Portal.cleanToken();
                        Portal.saveUser(json, date);
                        Portal.islogout(json.username);
                        Portal.unhandRedirect('index.html#success=1');
                        Portal.hand();
                    }
                });
            }
        } else if(param.success == 1) {
            if(token && uid) {
                Portal.hand();
                Portal.islogout(username);
            } else {
                Portal.unhandRedirect('index.html');
            }
        } else {
            Portal.redirect('index.html');
        }
    } else {
        if(token) {
            OAuth2.accessToken  = token;
            OAuth2.refreshToken = refreshToken;
        }
        if(uid) {
            Portal.hand();
            Portal.islogout(username);
        } else {
            Portal.islogin(OAuth2.getAuthorizeURL(config.redirectURI));
            Portal.unhand();
            Portal.releaseCookie();
        }
    }
    // loop
    setTimeout(function() {
        if(!$.cookie("ufsso_longmeng_portal_uid")) {
            Portal.ajaxCheckLogin();
        } else {
            Portal.ajaxKeepLogin();
        }
    }, 500);
    //初始化原始登录显示
    //$("#appsbytran_btn a").hide();
    //$("#appsbysso_btn a").show();
    //$("#nextBtn a").click();
    //初始化SSO登录显示
    $("#appsbytran_btn a").show();
    $("#appsbysso_btn a").hide();

    $.loadApps();
    $.loadAppsTran();
    $("#popup-login-container").height($(document).height());
});

Portal.ajaxCheckLogin = function() {
    OAuth2.checkLogin(function(json) {
        if(json.error) {
            setTimeout(Portal.ajaxCheckLogin, 5000);
        } else {
            var username = json.username;
            $("#popup-login-container").show();
            $("#popup-login").show();
            $("#popup-login form").prop('action',OAuth2.getAuthorizeURL(config.redirectURI, undefined, '1qa2ws3ed'));
            $("#logged-username").html(username);
            setTimeout(Portal.ajaxKeepLogin, 300000);
        }
    });
};
Portal.ajaxKeepLogin = function() {
    OAuth2.keepLogin(function(json) {
        if(!json.error) {
            setTimeout(Portal.ajaxKeepLogin, 300000);
        }
    });
};
// 绑定onbeforeunload事件
Portal.hand = function() {
    window.onbeforeunload = function() {
        return "为了你的帐号安全，请主动登出后再关闭此网页？";
    };
};
// 解绑onbeforeunload事件
Portal.unhand = function() {
    window.onbeforeunload = function() {
    };
};
Portal.unhandRedirect = function(url) {
    Portal.unhand();
    Portal.redirect(url);
};
Portal.redirect = function(url) {
    window.location = url;
};
Portal.saveToken = function(token, date) {
    $.cookie("ufsso_longmeng_portal_token", token.access_token, { expires:date });
    $.cookie("ufsso_longmeng_portal_expires_in", token.expires_in, { expires:date });
    if(token.refresh_token) {
        $.cookie("ufsso_longmeng_portal_refresh_token", token.refresh_token, { expires:date });
    }
};
Portal.saveUser = function(user, date) {
    $.cookie("ufsso_longmeng_portal_uid", user.uid, { expires:date });
    $.cookie("ufsso_longmeng_portal_username", user.username, { expires:date });
    $.cookie("ufsso_longmeng_portal_role", user.role, { expires:date });
};
Portal.cleanToken = function() {
    $.cookie("ufsso_longmeng_portal_token", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_expires_in", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_refresh_token", null, { expires:0 });
};
Portal.cleanUser = function() {
    $.cookie("ufsso_longmeng_portal_uid", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_username", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_role", null, { expires:0 });
};
Portal.islogin = $.islogin = function(url) {
    $("#ssologin_btn").show();
    $("#ssologin_btn").parent().show();
    $("#ssologout_btn").hide();
    $("#ssologout_btn").parent().hide();
    $("#ssolog_btn").hide();
    //$("#ssolog_btn").parent().hide();
    $("#ssousername_btn").hide();
    $("#ssousername_btn").parent().hide();
    $("#ssologing_btn").hide();
    $("#ssologing_btn").parent().hide();
    $("#ssologin_btn a").attr("href",url);
};
Portal.isloging = $.isloging = function() {
    $("#ssologin_btn").hide();
    $("#ssologout_btn").hide();
    $("#ssolog_btn").hide();
    $("#ssousername_btn").hide();
    $("#ssologing_btn").show();
    $("#ssologin_btn").parent().hide();
    $("#ssologout_btn").parent().hide();
    //$("#ssolog_btn").parent().hide();
    $("#ssousername_btn").parent().hide();
    $("#ssologing_btn").parent().show();
};
Portal.islogout = $.islogout = function(username) {
    $("#ssologin_btn").hide();
    $("#ssologout_btn").show();
    $("#ssolog_btn").show();
    $("#ssousername_btn").show();
    $("#ssologing_btn").hide();
    $("#ssologin_btn").parent().hide();
    $("#ssologout_btn").parent().show();
    //$("#ssolog_btn").parent().show();
    $("#ssousername_btn").parent().show();
    $("#ssologing_btn").parent().hide();
    $("#ssousername_btn span.btn_center").html(username);
};
Portal.refreshCookie = $.refreshCookie = function() {
    /*if($.cookie("ufsso_longmeng_portal_token")) {
        var date = new Date();
        date.setTime(date.getTime() + (expires_in * 1000));
        $.cookie("ufsso_longmeng_portal_uid",$.cookie("ufsso_longmeng_portal_uid"), { expires:date });
        $.cookie("ufsso_longmeng_portal_username", $.cookie("ufsso_longmeng_portal_username"), { expires:date });
        $.cookie("ufsso_longmeng_portal_role", $.cookie("ufsso_longmeng_portal_role"), { expires:date });
        $.cookie("ufsso_longmeng_portal_token", $.cookie("ufsso_longmeng_portal_token"), { expires:date });
        $.cookie("ufsso_longmeng_portal_expires_in", $.cookie("ufsso_longmeng_portal_expires_in"), { expires:date });
        $.cookie("ufsso_longmeng_portal_refresh_token", $.cookie("ufsso_longmeng_portal_refresh_token"), { expires:date });
    }*/
};
Portal.releaseCookie = $.releaseCookie = function() {
    $.cookie("ufsso_longmeng_portal_uid", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_username", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_role", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_token", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_expires_in", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_refresh_token", null, { expires:0 });
};
Portal.loadApps = $.loadApps = function(arg) {
    var role = $.cookie("ufsso_longmeng_portal_role");
    var role_flag = 1;
    if(role == '学生') { role_flag = 2; }
    if(role == '教师') { role_flag = 2; }
    $.post("portal/apps.php",{fromsession:1},function(response) {
        var ret = eval(response);
        var obj = ret.clients ? ret.clients : ret.data.list;
        for(var i = 0; i < obj.length;i++) {
            if(i == 0 ) {
                $("#appsbysso").html('<div class="RecentBlank"></div>');
            } else if (i%6 == 0) {
                $("#appsbysso").append('<div class="RecentBlank"></div><div class="clr"></div><div class="RecentBlank"></div>');
            }
            $("#appsbysso").append('<div class="Recent" ><a client-id="' + obj[i].clientId + '" href="' + obj[i].clientLocation + '" target="_blank" onclick="javascript:$.refreshCookie();"><span class="mask"></span><img border="0" src="' + obj[i].clientLogoUri + '" alt="' + obj[i].clientName + '"></img></a></div>');
        }
        if($.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0')) {
            $(".mask").hide();
            $(".Recent a img").css("top","0");
        }
        //$(".mask").hide();
        $.refreshCookie();
        //console.log(response);
    });
};
Portal.trans = [{
        "clientName":"校友管理系统",
        "clientLocation":"http://xiaoyou.lixin.edu.cn/",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/y_71717517.png",
        "clientDescribe":"校友管理系统",
        "clientVisible":1
    },{
        "clientName":"体育管理系统",
        "clientLocation":"http://sports.lixin.edu.cn/",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/y_71717519.png",
        "clientDescribe":"体育管理系统",
        "clientVisible":1
    },{
        "clientName":"招聘管理系统",
        "clientLocation":"http://hr.lixin.edu.cn:81/lixinindex.jsp",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/y_71717530.png",
        "clientDescribe":"招聘管理系统",
        "clientVisible":1
    },{
        "clientName":"一卡通",
        "clientLocation":"http://ecard.lixin.edu.cn/login.asp",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/y_71717537.png",
        "clientDescribe":"一卡通",
        "clientVisible":0
    },{
        "clientName":"学生补贴",
        "clientLocation":"http://xinzi.lixin.edu.cn:60/",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/y_71717597.png",
        "clientDescribe":"学生补贴",
        "clientVisible":2
    },{
        "clientName":"VPN服务",
        "clientLocation":"http://www.lixin.edu.cn/default.php?mod=c&s=ssf511661",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/logo_vpna.png",
        "clientDescribe":"VPN服务",
        "clientVisible":0
    },{
        "clientName":"财务综合查询",
        "clientLocation":"http://cw.lixin.edu.cn:8080/",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/icon_fin.png",
        "clientDescribe":"财务综合查询",
        "clientVisible":1
    },{
        "clientName":"历年财务查询",
        "clientLocation":"http://cw.lixin.edu.cn:81/",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/y_71717587-3.png",
        "clientDescribe":"历年财务查询",
        "clientVisible":1
    },{
        "clientName":"研究生招生",
        "clientLocation":"http://gse.lixin.edu.cn/",
        "clientLogoUri":"http://sso.lixin.edu.cn/images/y_71717598.png",
        "clientDescribe":"研究生招生",
        "clientVisible":1
    }];
Portal.loadAppsTran = $.loadAppsTran = function() {
    var obj = Portal.trans;
    for(var i = 0; i < obj.length;i++) {
        if(i == 0 ) {
            $("#appsbytrans").html('<div class="RecentBlank"></div>');
        } else if (i%6 == 0) {
            $("#appsbytrans").append('<div class="RecentBlank"></div><div class="clr"></div><div class="RecentBlank"></div>');
                }
        $("#appsbytrans").append('<div class="Recent" ><a href="' + obj[i].clientLocation + '" target="_blank" onclick="javascript:$.refreshCookie();"><span class="mask"></span><img border="0" src="' + obj[i].clientLogoUri + '" alt="' + obj[i].clientName + '"></img></a></div>');
            }
    if($.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0')) { $(".mask").hide();$(".Recent a img").css("top","0"); }
    //$(".mask").hide();
};
