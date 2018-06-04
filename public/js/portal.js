
var Portal = {};
var oauth2 = App.oauth2 = new SSOToOAuth2(config.clientId,config.clientSecret,config.redirectURI);

Portal.username = '';
Portal.column = 6;
Portal.role = "";
Portal.elections = null;
Portal.trans = [/*{
        "clientName":"校友管理系统",
        "clientLocation":"http://xiaoyou.lixin.edu.cn/",
        "clientLogoUri":"/images/y_71717517.png",
        "clientDescribe":"校友管理系统",
        "clientVisible":1
    },{
        "clientName":"体育管理系统",
        "clientLocation":"http://sports.lixin.edu.cn/",
        "clientLogoUri":"/images/y_71717519.png",
        "clientDescribe":"体育管理系统",
        "clientVisible":1
    },{
        "clientName":"招聘管理系统",
        "clientLocation":"http://hr.lixin.edu.cn:81/lixinindex.jsp",
        "clientLogoUri":"/images/y_71717530.png",
        "clientDescribe":"招聘管理系统",
        "clientVisible":1
    },{
        "clientName":"一卡通",
        "clientLocation":"http://ecard.lixin.edu.cn/login.asp",
        "clientLogoUri":"/images/y_71717537.png",
        "clientDescribe":"一卡通",
        "clientVisible":0
    },{
        "clientName":"学生补贴",
        "clientLocation":"http://xinzi.lixin.edu.cn:60/",
        "clientLogoUri":"/images/y_71717597.png",
        "clientDescribe":"学生补贴",
        "clientVisible":2
    },*/{
        "clientName":"VPN服务",
        "clientLocation":"http://www.lixin.edu.cn/default.php?mod=c&s=ssf511661",
        "clientLogoUri":"/images/logo_vpna.png",
        "clientDescribe":"VPN服务",
        "clientVisible":0
    }/*,{
        "clientName":"财务综合查询",
        "clientLocation":"http://cw.lixin.edu.cn:8080/",
        "clientLogoUri":"/images/icon_fin.png",
        "clientDescribe":"财务综合查询",
        "clientVisible":1
    },{
        "clientName":"历年财务查询",
        "clientLocation":"http://cw.lixin.edu.cn:81/",
        "clientLogoUri":"/images/y_71717587-3.png",
        "clientDescribe":"历年财务查询",
        "clientVisible":1
    },{
        "clientName":"研究生招生",
        "clientLocation":"http://gse.lixin.edu.cn/",
        "clientLogoUri":"/images/y_71717598.png",
        "clientDescribe":"研究生招生",
        "clientVisible":1
    }*/];
Portal.init = function() {
    var token = $.cookie("ufsso_longmeng_portal_token");
    var uid = $.cookie("ufsso_longmeng_portal_uid");
    var username = $.cookie("ufsso_longmeng_portal_username");
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
                oauth2.accessToken  = param.access_token;
                oauth2.refreshToken = param.refresh_token;
                //Portal.saveToken(param, date);
            }
            if(!uid) {
                Portal.isloging();
                oauth2.getUserInfo(function(json) {
                    if(json.error) {
                        Portal.redirect(oauth2.getAuthorizeURL(config.redirectURI));
                    } else {
                        var date = new Date();
                        var expires_in = 259000;
                        date.setTime(date.getTime() + (expires_in * 1000));
                        Portal.cleanToken();
                        Portal.saveUser(json, date);
                        Portal.islogout(json.username);
                        Portal.unhandRedirect('index.html#success=1');
                        Portal.hand();
                        Portal.load();
                    }
                    // checking
                    Portal.ajaxCheck();
                });
            } else {
                Portal.unhandRedirect('index.html');
                //Portal.hand();
            }
        } else if(param.success == 1) {
            if(token && uid) {
                Portal.hand();
                Portal.islogout(username);
                // checking
                Portal.ajaxCheck();
                // load apps
                Portal.load();
            } else {
                Portal.unhandRedirect('index.html');
            }
        } else {
            Portal.redirect('index.html');
        }
    } else {
        if(token) {
            oauth2.accessToken  = token;
            oauth2.refreshToken = refreshToken;
        }
        if(uid) {
            Portal.hand();
            Portal.islogout(username);
        } else {
            Portal.islogin(oauth2.getAuthorizeURL(config.redirectURI));
            Portal.unhand();
            Portal.releaseCookie();
        }
        // checking
        Portal.ajaxCheck();
        // load apps
        Portal.load();
    }
};
// listen
Portal.listen = function() {
    // listen
    $(window).hashchange(function() {
        var hash = document.location.hash.substring(1);
        if(hash.substring(0,1) == '!') {
           hash = hash.substring(1);
        }
    });
    $(window).unload(function() {
        //Portal.cleanToken();
        //Portal.cleanUser();
    });
    $("#ssologout_btn a").click(function() {
        Portal.cleanToken();
        Portal.cleanUser();
        Portal.unhandRedirect(oauth2.getLogoutURL(config.redirectURI));
    });
    // set popup
    $("#popup-login-container").height($(document).height());

};
Portal.listenElection = function() {
    $('.operator.add, .operator.del').click(function() {
        var el = $(this);
        var clientId = $(this).attr('data-clientId');
        var hasAdd = $(this).hasClass('add');
        var hasDel = $(this).hasClass('del');
        var option = {};
        //option.fromsession = false;
        if(clientId && hasAdd) {
            Portal.Server.addElection(clientId, {}, function(err, data) {
                if(!err) {
                    option.election = false;
                    Portal.elections = null;
                    Portal.reload(option);
                }
            });
        } else if(clientId && hasDel) {
            Portal.Server.delElection(clientId, {}, function(err, data) {
                if(!err) {
                    option.election = true;
                    Portal.elections = null;
                    Portal.reload(option);
                }
            });
        }
    });
};
// check
Portal.checkElection = function(client_id, option) {
    if(Portal.elections) {
        for (var i = 0; i < Portal.elections.length; i++) {
            if(Portal.elections[i].clientId == client_id) {
                return true;
            }
        }
        return false;
    } else {
        return false;
    }
};
Portal.load = function() {
    // load apps
    Portal.loadApps();
    Portal.loadAppsTran();
};
Portal.checkTimer;
Portal.keepTimer;
Portal.keepGap = 15000;
Portal.checkGap = 15000;
//Portal.expires = 1800000;//30分钟
Portal.ajaxCheck = function() {
    //setTimeout(function() {
    if(!$.cookie("ufsso_longmeng_portal_uid")) {
        Portal.ajaxCheckLogin();
    } else {
        Portal.ajaxKeepLogin();
    }
    //}, 5000);
};
Portal.ajaxCheckLogin = function() {
    oauth2.checkLogin(function(json) {
        if(json.error) {
            if(!Portal.checkTimer) {
                Portal.checkTimer = setInterval(Portal.ajaxCheckLogin, Portal.checkGap);
            }
            if(Portal.keepTimer) {
                clearInterval(Portal.keepTimer);
            }
        } else {
            var username = json.username;
            $("#popup-login-container").show();
            $("#popup-login").show();
            $("#popup-login form").prop('action',oauth2.getAuthorizeURL(config.redirectURI, undefined, '1qa2ws3ed'));
            $("#logged-username").html(username);
            if(!Portal.keepTimer) {
                Portal.keepTimer = setInterval(Portal.ajaxKeepLogin, Portal.keepGap);
            }
            if(Portal.checkTimer) {
                clearInterval(Portal.checkTimer);
            }
        }
    });
};
Portal.ajaxKeepLogin = function() {
    oauth2.keepLogin(function(json) {
        if(!json.error) {
            if(!Portal.keepTimer) {
                Portal.keepTimer = setInterval(Portal.ajaxKeepLogin, Portal.keepGap);
            }
            if(Portal.checkTimer) {
                clearInterval(Portal.checkTimer);
            }
        } else {
            Portal.releaseCookie();
            if($("#ssologout_btn").is(":visible")) {
                //在首页已登录
                Portal.unhandRedirect(oauth2.getAuthorizeURL(config.redirectURI));
            } else {
                //在首页未登录
                $("#popup-login-container").hide();
                $("#popup-login").hide();
                $("#popup-login form").prop('action', '');
                $("#logged-username").html('');
                if(!Portal.checkTimer) {
                    Portal.checkTimer = setInterval(Portal.ajaxCheckLogin, Portal.checkGap);
                }
                if(Portal.keepTimer) {
                    clearInterval(Portal.keepTimer);
                }
            }
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
    $.cookie("ufsso_longmeng_portal_uid", user.uid, { domain: '', path: '' });
    $.cookie("ufsso_longmeng_portal_username", user.username, { domain: '', path: '' });
    $.cookie("ufsso_longmeng_portal_role", user.role, { domain: '', path: '' });
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
Portal.setSessionCookie = function(name,value,cookiePath){  
    var isIE=!-[1,];//判断是否是ie核心浏览器  
    if(isIE){  
        if(value){  
            var expire = "; expires=At the end of the Session";  
            var path="";  
            if(cookiePath!=null){  
                path="; path="+cookiePath;  
            }  
            document.cookie = name + "=" + escape(value) + expire+path;  
        }  
    }else{    
        if(value){  
            var expire = "; expires=Session";  
            var path="";  
            if(cookiePath!=null){  
                path="; path="+cookiePath;  
            }  
            document.cookie = name + "=" + escape(value) + expire+path;  
        }  
    }  
};
Portal.detectRole = function(role) {
    return !role ? '' : (role == '教师' ? 'teacher' : (role == '学生' ? 'student' : (role == '其他' ? "other" : 'teacher')));
};
Portal.islogin = function(url) {
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
Portal.isloging = function() {
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
Portal.islogout = function(username) {
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
Portal.refreshCookie = function() {
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
Portal.releaseCookie = function() {
    $.cookie("ufsso_longmeng_portal_uid", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_username", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_role", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_token", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_expires_in", null, { expires:0 });
    $.cookie("ufsso_longmeng_portal_refresh_token", null, { expires:0 });
};
Portal.loadApps = function(arg) {
    var role = $.cookie("ufsso_longmeng_portal_role");
    var role_flag = 1;
    if(role == '学生') { role_flag = 2; }
    if(role == '教师') { role_flag = 2; }
    var columns = $('#app_columns').val();
    columns = columns ? parseInt(columns) : 6;
    $.post("portal/apps.php",{fromsession:1},function(response) {
        var ret = eval(response);
        var obj = ret.clients ? ret.clients : ret.data.list;
        for(var i = 0; i < obj.length;i++) {
            if(i == 0 ) {
                //$("#appsbysso").html('<div class="RecentBlank"></div>');
            } else if (i%columns == 0) {//<div class="RecentBlank"></div><div class="RecentBlank"></div>
                $("#appsbysso").append('<div class="clr"></div>');
            }
            $("#appsbysso").append('<div class="Recent" ><a client-id="' + obj[i].clientId + '" href="' + obj[i].clientLocation + '" target="_blank" onclick="javascript:Portal.refreshCookie();"><div><span class="mask"></span><img border="0" src="' + obj[i].clientLogoUri + '" alt="' + obj[i].clientName + '"></img></div></a></div>');
        }
        $('#appsbysso').append('<div class="clr"></div>');
        if($.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0')) {
            $(".mask").hide();
            $(".Recent a img").css("top","0");
        }
        //$(".mask").hide();
        Portal.refreshCookie();
        //console.log(response);
    });
};
Portal.loadAppsTran = function() {
    var obj = Portal.trans;
    var columns = $('#app_columns').val();
    columns = columns ? parseInt(columns) : 6;
    for(var i = 0; i < obj.length;i++) {
        if(i == 0 ) {
            //$("#appsbytrans").html('<div class="RecentBlank"></div>');
        } else if (i%columns == 0) {//<div class="RecentBlank"></div><div class="RecentBlank"></div>
            $("#appsbytrans").append('<div class="clr"></div>');
        }
        $("#appsbytrans").append('<div class="Recent" ><a href="' + obj[i].clientLocation + '" target="_blank" onclick="javascript:Portal.refreshCookie();"><div><span class="mask"></span><img border="0" src="' + obj[i].clientLogoUri + '" alt="' + obj[i].clientName + '"></img></div></a></div>');
    }
    $('#appsbytrans').append('<div class="clr"></div>');
    if($.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0')) { $(".mask").hide();$(".Recent a img").css("top","0"); }
    //$(".mask").hide();
};
// portal server
Portal.Server = {};
Portal.Server.appsElection = Portal.appsElection = function (option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    $.ajax({
        url : "/portal/election.php",
        type :'post',
        data: {},
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(App.Util.detectError(o));
            }
        },
        complete:function(){
        },
        error:function(){
            //alert('login failure!');
            //$("#LoginErrorID").html('登录失败!要不再来次');
        }
    });
};
Portal.Server.addElection = Portal.addElection = function (client_id, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    var data = {};
    data.key = 'add';
    data.client_id = client_id;
    $.ajax({
        url : "/portal/election.php",
        type :'post',
        data: data,
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(App.Util.detectError(o));
            }
        },
        complete:function(){
        },
        error:function(){
            //alert('login failure!');
            //$("#LoginErrorID").html('登录失败!要不再来次');
        }
    });
};
Portal.Server.delElection = Portal.delElection = function (client_id, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    var data = {};
    data.key = 'del';
    data.client_id = client_id;
    $.ajax({
        url : "/portal/election.php",
        type :'post',
        data: data,
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(App.Util.detectError(o));
            }
        },
        complete:function(){
        },
        error:function(){
            //alert('login failure!');
            //$("#LoginErrorID").html('登录失败!要不再来次');
        }
    });
};
Portal.Server.apps = Portal.apps = function(role, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数
    $.ajax({
        url : "../portal/apps.php",
        type :'post',
        data:{
            role : role
        },
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(App.Util.detectError(o));
            }
        },
        complete:function(){
        },
        error:function(){
            //alert('login failure!');
            //$("#LoginErrorID").html('登录失败!要不再来次');
        }
    });
};
Portal.Server.appsTran = Portal.appsTran = function(role, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    var data = [];
    for(var i = 0; i < Portal.trans.length; i++) {
        var client = Portal.trans[i];
        if(client.clientVisible == 1 && role == 'teacher') {
            data.push(client);
        } else if(client.clientVisible == 2 && role == 'student') {
            data.push(client);
        } else if(client.clientVisible == 3 && role == 'other') {
            data.push(client);
        } else if(client.clientVisible == 0) {
            data.push(client);
        }
    }
    callback(null, data);
};

$(document).ready(function(){
    Portal.init();
    // listen
    Portal.listen();
});
