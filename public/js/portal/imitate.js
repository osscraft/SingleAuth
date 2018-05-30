Portal.loadApps = function(arg) {
    //var role = $.cookie("ufsso_longmeng_portal_role");
    var role="学生";
    var role_flag = 1;
    if(role == '学生') { role_flag = 2; }
    if(role == '教师') { role_flag = 2; }
    
    $.post("portal/apps.php",{fromesession:1},function(response) {
        var ret = eval(response);
        var obj = ret.clients ? ret.clients : ret.data.list;
        for(var i = 0; i < obj.length;i++) {
            if(i == 0 ) {
                $("#appsbysso").html('<div class="RecentBlank"></div>');
            } else if (i%8 == 0) {
                $("#appsbysso").append('<div class="RecentBlank"></div><div class="clr"></div><div class="RecentBlank"></div>');
            }
            $("#appsbysso").append('<div class="Recent" ><a href="' + obj[i].clientLocation + '" target="_blank" onclick=""><div><span class="mask"></span><img border="0" src="' + obj[i].clientLogoUri + '" alt="' + obj[i].clientName + '"></img></div></a></div>');
        }
        if($.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0')) { $(".mask").hide();$(".Recent a img").css("top","0"); }
       // $.refreshCookie();
    });
};         
Portal.loadAppsTran = function() {
    var obj = [{
            "clientName":"","clientLocation":"http://xiaoyou.lixin.edu.cn/","clientLogoUri":"images/y_71717517.png"
         },{
            "clientName":"","clientLocation":"http://sports.lixin.edu.cn/","clientLogoUri":"images/y_71717519.png"
         },{
            "clientName":"","clientLocation":"http://hr.lixin.edu.cn:81/lixinindex.jsp","clientLogoUri":"images/y_71717530.png"
         },{
            "clientName":"","clientLocation":"http://ecard.lixin.edu.cn/login.asp","clientLogoUri":"images/y_71717537.png"
         },{
            "clientName":"","clientLocation":"http://xinzi.lixin.edu.cn:60/","clientLogoUri":"images/y_71717597.png"
         },{
            "clientName":"","clientLocation":"http://www.lixin.edu.cn/default.php?mod=c&s=ssf511661","clientLogoUri":"images/logo_vpna.png"
         },{
            "clientName":"","clientLocation":"http://cw.lixin.edu.cn:8080/","clientLogoUri":"images/icon_fin.png"
         },{
            "clientName":"","clientLocation":"http://cw.lixin.edu.cn:81/","clientLogoUri":"images/y_71717587-3.png"
         },{
            "clientName":"","clientLocation":"http://gse.lixin.edu.cn/","clientLogoUri":"images/y_71717598.png"
         }];
    for(var i = 0; i < obj.length;i++) {
        if(i == 0 ) {
            $("#appsbytrans").html('<div class="RecentBlank"></div>');
        } else if (i%8 == 0) {
            $("#appsbytrans").append('<div class="RecentBlank"></div><div class="clr"></div><div class="RecentBlank"></div>');
        }
        $("#appsbytrans").append('<div class="Recent" ><a href="' + obj[i].clientLocation + '" target="_blank" onclick=""><div><span class="mask"></span><img border="0" src="' + obj[i].clientLogoUri + '" alt="' + obj[i].clientName + '"></img></div></a></div>');
    }
    if($.browser.msie && ($.browser.version == '6.0' || $.browser.version == '7.0')) { $(".mask").hide();$(".Recent a img").css("top","0"); }
};