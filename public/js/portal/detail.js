// JavaScript Document
Portal.username = '';
Portal.column = 6;
Portal.role="";
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
        $('.switcher.log').closest('.switcher-item').hide();
        Portal.role = Portal.role ? Portal.role : 'teacher';//没有值时使用教师作为默认值
    } else {
        $('.switcher.election').closest('.switcher-item').show();
        $('.switcher.other').closest('.switcher-item').show();
        $('.switcher.statistics').closest('.switcher-item').show();
        $('.switcher.log').closest('.switcher-item').show();
        if(role != 'other') {
            $('.switcher.other').closest('.switcher-item').hide();
        }
        Portal.role = Portal.role ? Portal.role : role;
    }
    // 卡片激活
    $('.switcher-item').removeClass('on');
    $('.switcher.' + Portal.role).closest('.switcher-item').addClass('on');

    if(uid) {
        //加载自定义
        option.fn = function(err, data) {
            if(!err) {
                Portal.elections = data.clients ? data.clients : data.list;//更新
                if(Portal.elections.length > 0) {
                    // 有,返回
                    $('.switcher-item').removeClass('on');
                    $('.switcher.election').closest('.switcher-item').addClass('on');
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
Portal.loadApps = function(role, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var column = this.column;
    var uid = $.cookie("ufsso_longmeng_portal_uid");
    $('.loader').show();
    Portal.Server.apps(role, option, function(err, data) {
        if(!err) {
            var obj = data.clients ? data.clients : data.list;
            var html = '';
            for(var i = 0; i < obj.length;i++) {
                var client = obj[i];
                var showOperate = !Portal.checkElection(client.clientId);
                obj[i].clientLogoUri = obj[i].clientLogoUri ? obj[i].clientLogoUri : "/images/ICON_BackGround_Raw.png";
                if(i%column == 0) {
                    html += '<div class="apps">';
                }
                html += '<div class="app' + (i%column > 0 && (column - i%column) > 1 ? ' app2' : '')  + (uid && showOperate?' operation':'') + '">' +
                        (uid && showOperate? '<div class="operator-container"><div class="operator-item"><a class="operator add" data-clientId="' + client.clientId + '" data-role="' + role + '" title="加入自定义"><img class="operator-icon" src="/images/plus.png" width="20"></a></div></div>' : '') + 
                        '<div class="applogo">' +
                            '<a href="' + client.clientLocation + '" target="_blank">' +
                                '<img width="90" height="90" border="0" src="' + client.clientLogoUri + '" alt="' + client.clientName + '">' +
                            '</a>' +
                            (config.portal_app_has_title ? '<a class="title" href="' + client.clientLocation + '" target="_blank">' + client.clientName + '</a>' : '') +
                        '</div>' +
						'<span class="mask"></span>'+
                        /*'<div class="detail">' +
                            '<p><a href="' + client.clientLocation + '" target="_blank">' + client.clientName + '</a></p>' +
                            '<div class="spans"><span>' + client.clientDescribe + '</span></div>' +
                        '</div>' +*/
                    '</div>';
                if(i%column == column - 1 || i == data.length - 1) {
                    html += '</div>';
                }
            }
            $('#appsbysso').html(html);
            $('.loader').hide();
            Portal.listenElection();
        }
    });
};
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
            Portal.elections = obj;//更新
            for(var i = 0; i < obj.length;i++) {
                var client = obj[i];
                if(i%column == 0) {
                    html += '<div class="apps">';
                }
                html += '<div class="app' + (i%column > 0 && (column - i%column) > 1 ? ' app2' : '')  + (uid?' operation':'') + '">' +
                        (uid?'<div class="operator-container"><div class="operator-item"><a class="operator del" data-clientId="' + client.clientId + '"><img class="operator-icon" src="/images/minus.png" width="20"></a></div></div>':'') + 
                        '<div class="applogo">' +
                            '<a href="' + client.clientLocation + '" target="_blank">' +
                                '<img width="90" height="90" border="0" src="' + client.clientLogoUri + '" alt="' + client.clientName + '">' +
                            '</a>' +
                            (config.portal_app_has_title ? '<a class="title" href="' + client.clientLocation + '" target="_blank">' + client.clientName + '</a>' : '') +
                        '</div>' +
						'<span class="mask"></span>'+
                        /*'<div class="detail">' +
                            '<p><a href="' + client.clientLocation + '" target="_blank">' + client.clientName + '</a></p>' +
                            '<div class="spans"><span>' + client.clientDescribe + '</span></div>' +
                        '</div>' +*/
                    '</div>';
                if(i%column == column - 1 || i == data.length - 1) {
                    html += '</div>';
                }
            }
            $('#appsbysso').html(html);
            $('.loader').hide();
            Portal.listenElection();
        }
        // callback
        option.fn(err, data);
    });
};
Portal.loadAppsTran = function(role, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var column = this.column;
    Portal.Server.appsTran(role, option, function(err, data) {
        var html = '';
        for(var i = 0; i < data.length; i++) {
            var client = data[i];
            if(i%column == 0) {
                html += '<div class="apps">';
            }
            html += '<div class="app' + (i%column > 0 && (column - i%column) > 1 ? ' app2' : '')  + '">' +
                    '<div class="applogo">' +
                        '<a href="' + client.clientLocation + '" target="_blank">' +
                            '<img width="90" height="90" border="0" src="' + client.clientLogoUri + '" alt="' + client.clientName + '">' +
                        '</a>' +
                        //'<a class="title" href="' + client.clientLocation + '" target="_blank">' + client.clientName + '</a>' +
                    '</div>' +
					'<span class="mask"></span>'+
                    /*'<div class="detail">' +
                        '<p><a href="' + client.clientLocation + '" target="_blank">' + client.clientName + '</a></p>' +
                        '<div class="spans"><span>' + client.clientDescribe + '</span></div>' +
                    '</div>' +*/
                '</div>';
            if(i%column == column - 1 || i == data.length - 1) {
                html += '</div>';
            }
        }
        $('#appsbytran').html(html);
    });
};
Portal.loadLoguser = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var newpKey = 'page';
    var rpKey = 'pageSize';
    var urlstr = '/portal/log.php';
    var grid = {
        url: urlstr,
        dataType: 'json',
        colModel : [
            {display: '序号', name : 'id', width : 50, align: 'center' },
            {display: '登录时间', name : 'time', width : 140, align: 'center'},
            //{display: 'ClientId', name : 'clientId', width : 180, align: 'left',hide: true},
            //{display: 'FacilityHost', name : 'facilityHost', width : 180,  align: 'left',hide: true},
            {display: '应用名称', name : 'clientName', width : 160,  align: 'center'},
            {display: '帐号', name : 'username', width : 100,  align: 'center'},
            //{display: 'Success', name : 'success', width : 80,  align: 'center',hide: true},
            {display: '成功登录', name : 'success', width : 80,  align: 'center'},
            {display: '用户IP', name : 'ip', width : 100,  align: 'center'},
            {display: '操作系统', name : 'os', width : 100,  align: 'center'},
            {display: '浏览器', name : 'browser', width : 100,  align: 'center'}
            ],
        searchitems: [
            {display: '登录时间', name: 'time' },
            {display: '应用名称', name: 'clientName' },
            {display: '成功登录', name: 'success' },
            {display: '用户IP', name: 'ip' },
            {display: '操作系统', name: 'os' },
            {display: '浏览器', name: 'browser' }
            ],
        usepager: true,
        useRp: true,
        rp: 15,
        width: 1000,
        height: 360,
        resizable: false,
        autoload: false,
        title: '用户登录信息日志报表',
        procmsg: '加载中,请稍等...',
        nomsg: '没有信息', 
        pagestat: '显示 {from} ~ {to} 共{total} '
    };
    if(option[newpKey]) { grid.newp = parseInt(option[newpKey],10); }
    if(option[rpKey]) { grid.rp = parseInt(option[rpKey],10); }
    $("#log").flexigrid(grid);
    $("#log").flexReload();
};
//
Portal.clickTab = function(el, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var tab = $(el).attr('tab');
    var item = $(el).closest('.switcher-item');
    var ison = item.hasClass('on');
    // render 
    if(ison) {
        //激活状态下再点击，取消激活
        //$('.switcher-item').removeClass('on');
    } else {
        $('.switcher-item').removeClass('on');
        item.addClass('on');
    }
    /*var role = $.cookie("ufsso_longmeng_portal_role");*/
    if(tab == 'login') {
        Portal.redirect(oauth2.getAuthorizeURL(config.redirectURI));
        return;
    } else if(tab =='logout') {
        Portal.releaseCookie();
        Portal.redirect(oauth2.getLogoutURL(config.redirectURI));
        return;
    } else if(tab == 'teacher') {
        //option.fromsession = ison ? 1 : ''; 
        option.fromsession = '';
        Portal.role = "teacher";
        $('.center').show();
        $('.center.hide').hide();
        Portal.reload(option);
    } else if(tab == 'student') {
        //option.fromsession = ison ? 1 : '';
        option.fromsession = '';
        Portal.role = "student";
        $('.center').show();
        $('.center.hide').hide();
        Portal.reload(option);
    } else if(tab == 'other') {
        //option.fromsession = ison ? 1 : '';
        option.fromsession = '';
        Portal.role = "other";
        $('.center').show();
        $('.center.hide').hide();
        Portal.reload(option);
    } else if(tab == 'election') {
        //option.election = ison ? false : true;
        option.election = true;
        //Portal.role = "student";
        $('.center').show();
        $('.center.hide').hide();
        //$('.center.log').hide();
        Portal.reload(option);
    } else if(tab == 'log') {
        $('.center').hide();
        $('.center.log').show();
        Portal.loadLoguser(option);
    } else if(tab == 'statistics') {
        $('.center').hide();
        $('.center.stat').show();
        Portal.Statistics.load(option);
    } else {

    }
};