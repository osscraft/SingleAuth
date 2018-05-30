// listen
Portal.listenDescription = function() {
    $("#appsbysso .case_item").each(function(index){
        $(this).mouseover(function(){
            Portal.showSsoDesc(index);
        })
    });
};
// show
Portal.showTranDesc = function (d){
    $(".case_info_box").attr("style","display:none");
    $("#appsbytran .case_info_box").each(function(index){
        if(d==index){
            var num=index%6;
            $(this).find(".info_box_arrow").attr("style","left:"+(142*num+29)+"px;");
            $(this).removeAttr("style");
        }else{
            $(this).attr("style","display:none");
        }
    });     
};
Portal.showSsoDesc = function (d){
    $(".case_info_box").attr("style","display:none");
    $("#appsbysso .case_info_box").each(function(index){
        if(d==index){
            var num=index%6;
            $(this).find(".info_box_arrow").attr("style","left:"+(142*num+29)+"px;");
            $(this).removeAttr("style");
        }else{
            $(this).attr("style","display:none");
        }
    });     
};
// load
Portal.load = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.election = _.isUndefined(option.election) ? false : option.election;
    option.fromsession = false;// 

    var role = Portal.detectRole($.cookie("ufsso_longmeng_portal_role"));
    var uid = $.cookie("ufsso_longmeng_portal_uid");
    if(option.fromsession) {
        Portal.role = role;
    }

    if(!uid) {
        $('.switcher.election').closest('.switcher-item').hide();
        $('.switcher.other').closest('.switcher-item').hide();
        $('.switcher.statistics').closest('.switcher-item').hide();
        Portal.role = Portal.role ? Portal.role : 'teacher';//没有值时使用教师作为默认值
    } else {
        $('.switcher.election').closest('.switcher-item').show();
        $('.switcher.other').closest('.switcher-item').show();
        $('.switcher.statistics').closest('.switcher-item').show();
        if(role != 'other') {
            $('.switcher.other').closest('.switcher-item').hide();
        }
        Portal.role = Portal.role ? Portal.role : role;
    }
    // 卡片激活
    $('.switcher-item').removeClass('active');
    $('.switcher.' + Portal.role).closest('.switcher-item').addClass('active');

    if(uid) {
        //加载自定义
        option.fn = function(err, data) {
            if(!err) {
                Portal.elections = data.clients ? data.clients : data.list;//更新
                if(Portal.elections.length > 0) {
                    // 有,返回
                    $('.switcher-item').removeClass('active');
                    $('.switcher.election').closest('.switcher-item').addClass('active');
                    return;
                }
            }
            // 没有自定义
            Portal.loadApps(Portal.role, option);
            Portal.loadAppsTran(Portal.role, option);
        };
        Portal.loadAppsElection(option);
        Portal.loadAppsTran(Portal.role, option);
    } else {
        Portal.loadApps(Portal.role, option);
        Portal.loadAppsTran(Portal.role, option);
    }
};
// reload
Portal.reload = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.election = _.isUndefined(option.election) ? false : option.election;
    option.fromsession = false;// 

    var uid = $.cookie("ufsso_longmeng_portal_uid");
    
    if(option.election && uid) {
        Portal.loadAppsElection(option);
        Portal.loadAppsTran(Portal.role, option);
    } else {
        //先更新自定义的
        if(Portal.elections == null && uid) {
            // NUll时更新
            Portal.Server.appsElection({}, function(err, data) {
                if(!err) {
                    Portal.elections = data.clients ? data.clients : data.list;//更新
                    Portal.loadApps(Portal.role, option);
                    Portal.loadAppsTran(Portal.role, option);
                }
            });
        } else {
            Portal.loadApps(Portal.role, option);
            Portal.loadAppsTran(Portal.role, option);
        }
    }
};
// load use election apps
Portal.loadAppsElection = function (option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.fn = _.isFunction(option.fn) ? option.fn : function() {};//回调函数

    var column = this.column;
    var uid = $.cookie("ufsso_longmeng_portal_uid");
	 $('.loader').show();
    Portal.Server.appsElection(option, function(err, data) {
        if(!err) {
            
            var obj = data.clients ? data.clients : data.list;
            var html = '';
            var html1 = '';
            for(var i = 0; i < obj.length;i++) {
                var client = obj[i];
                if(i==0){
                    html1 = html1+'<div class="case_info_box">';
                }else{
                    html1 = html1+'<div class="case_info_box" style="display:none">';
                }
                html1 = html1 + '<div class="info_table_container">'+
                                client.clientDescribe +
                            '</div>'+
                            '<div class="loading_mark_container" node-type="info_table" style="display:none;"><i class="loading_mark"></i></div>'+
                            '<div class="info_box_arrow" node-type="arrow" style="left:26px;"><div class="info_box_arrow_inner"></div></div>'+
                           '</div>';
                if(i%column == 0) {
                    html += '<div class="case_box">'+
                                '<div class="case_list_container">'+
                                    '<ul class="case_list">';
                }
                html = html + '<li class="case_item' + (uid?' operation':'') + '">'+
                            (uid?'<div class="operator-container"><div class="operator-item"><a class="operator del" data-clientId="' + client.clientId + '"><img class="operator-icon" src="/images/minus.png" width="20"></a></div></div>':'') + 
                            '<p class="case_cover">'+
                            '<a class="case_cover_link" href="'+ client.clientLocation +'" target="_blank">'+
                            '<img class="case_cover_img" width="80" height="80" border="0" src="'+client.clientLogoUri+'" title="'+client.clientName+'"/>'+
                            '</a></p>'+
                            '<p class="case_name"><a class="case_name_link" href="' + client.clientLocation + '" target="_blank" title="'+client.clientName+'">'+client.clientName+'</a></p>'+
                        '</li>';
                if(i%column == column - 1 || i == obj.length - 1) {
                    html += '</ul>'+
                            '</div>'+
                            '</div>'+
                            '<div class="clr"></div>'+
                            html1+
                            '<div class="clr"></div>';
                    html1='';
                }
            }
            html+='<div class="clr"></div>';
            $('#appsbysso').html(html);
			$('.loader').hide();
        }
        Portal.listenElection();
        Portal.listenDescription();
        // callback
        option.fn(err, data);
    });
};
//
Portal.loadApps = function(role, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    var column = this.column;
    var uid = $.cookie("ufsso_longmeng_portal_uid");
	$('.loader').show();
    Portal.Server.apps(role, option, function(err, data) {
        if(!err) {
            var obj = data.clients ? data.clients : data.list;
            var html = '';
            var html1 = '';
            for(var i = 0; i < obj.length;i++) {
                var client = obj[i];
                var showOperate = !Portal.checkElection(client.clientId);
                if(i==0){
                    html1 = html1+'<div class="case_info_box">';
                }else{
                    html1 = html1+'<div class="case_info_box" style="display:none">';
                }
                html1 = html1 + '<div class="info_table_container">'+
                                client.clientDescribe +
                            '</div>'+
                            '<div class="loading_mark_container" node-type="info_table" style="display:none;"><i class="loading_mark"></i></div>'+
                            '<div class="info_box_arrow" node-type="arrow" style="left:26px;"><div class="info_box_arrow_inner"></div></div>'+
                           '</div>';
                if(i%column == 0) {
                    html += '<div class="case_box">'+
                                '<div class="case_list_container">'+
                                    '<ul class="case_list">';
                }
                html = html + '<li class="case_item' + (uid?' operation':'') + '">'+
                            (uid && showOperate? '<div class="operator-container"><div class="operator-item"><a class="operator add" data-clientId="' + client.clientId + '" data-role="' + role + '" title="加入自定义"><img class="operator-icon" src="/images/plus.png" width="20"></a></div></div>' : '') + 
                            '<p class="case_cover">'+
                            '<a class="case_cover_link" href="'+ client.clientLocation +'" target="_blank">'+
                            '<img class="case_cover_img" width="80" height="80" border="0" src="'+client.clientLogoUri+'" title="'+client.clientName+'"/>'+
                            '</a></p>'+
                            '<p class="case_name"><a class="case_name_link" href="' + client.clientLocation + '" target="_blank" title="'+client.clientName+'">'+client.clientName+'</a></p>'+
                        '</li>';
                if(i%column == column - 1 || i == obj.length - 1) {
                    html += '</ul>'+
                            '</div>'+
                            '</div>'+
                            '<div class="clr"></div>'+
                            html1+
                            '<div class="clr"></div>';
                    html1='';
                }
            }
            html+='<div class="clr"></div>';
            $('#appsbysso').html(html);
			 $('.loader').hide();
        }
        Portal.listenElection();
        Portal.listenDescription();
    });
};
Portal.loadAppsTran = function(role, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    
    var column = this.column;
    Portal.Server.appsTran(role, option, function(err, data) {
        var html = '';
        var html1 = '';
        for(var i = 0; i < data.length;i++) {
            var client = data[i];
            html1 = html1 +'<div class="case_info_box" style="display:none">' +
                        '<div class="info_table_container">'+
                            client.clientDescribe +
                        '</div>'+
                        '<div class="loading_mark_container" node-type="info_table" style="display:none;"><i class="loading_mark"></i></div>'+
                        '<div class="info_box_arrow" node-type="arrow" style="left:26px;"><div class="info_box_arrow_inner"></div></div>'+
                       '</div>';
            if(i%column == 0) {
                html += '<div class="case_box">'+
                            '<div class="case_list_container">'+
                                '<ul class="case_list">';
            }
            html = html + '<li class="case_item">'+
                        '<p class="case_cover">'+
                        '<a class="case_cover_link" href="'+ client.clientLocation +'" target="_blank">'+
                        '<img class="case_cover_img" width="80" height="80" border="0" src="'+client.clientLogoUri+'"/>'+
                        '</a></p>'+
                        '<p class="case_name"><a class="case_name_link" href="' + client.clientLocation + '" target="_blank">'+client.clientName+'</a></p>'+
                    '</li>';
            if(i%column == column - 1 || i == data.length - 1) {
                html += '</ul>'+
                        '</div>'+
                        '</div>'+
                        '<div class="clr"></div>'+
                        html1+
                        '<div class="clr"></div>';
                html1='';
            }
        }
        html+='<div class="clr"></div>';
        $('#appsbytran').html(html);
        $("#appsbytran .case_item").each(function(index){
            $(this).mouseover(function(){
                Portal.showTranDesc(index);
            })
        });
        /*$(".case_item").each(function(){
            $(this).mouseout(function(){
                $(".case_info_box").each(function(){
                    $(this).attr("style","display:none");
                })
            })
        });*/

    });
};
// click event
Portal.clickTab = function(el, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var tab = $(el).attr('tab');
    var roles = $.cookie("ufsso_longmeng_portal_role");
    var item = $(el).closest('.switcher-item');
    var isactive = item.hasClass('active');
    if(isactive) {
        //激活状态下再点击，取消激活
        //$('.switcher-item').removeClass('on');
    } else {
        $('.switcher-item').removeClass('active');
        item.addClass('active');
    }
    if(tab == 'teacher') {
        option.fromsession = '';
        Portal.role = "teacher";
		Portal.reload();
    } else if(tab == 'student') {
        option.fromsession = '';
        Portal.role = "student";
		Portal.reload();
    } else if(tab == 'other') {
        //option.fromsession = ison ? 1 : '';
        option.fromsession = '';
        Portal.role = "other";
        Portal.reload(option);
    } else if(tab == 'election') {
        //option.election = ison ? false : true;
        option.election = true;
        Portal.reload(option);
    } else {

    }
};