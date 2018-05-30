$(document).ready(function() {
    var username = $.cookie("ufsso_longmeng_portal_uid");
    var hash = document.location.hash;
    var params = {};
    var keyKey = 'key';
    var newpKey = 'page';
    var rpKey = 'pageSize';
    var current = '';

    $.inithash = function() {
        if(hash.substring(0,2) == '#!') {
           params = $.deparam(hash.substring(2));
           $.dispatch(params);
        } else if(hash.substring(0,1) == '#'){
           $.execute(hash.substring(1));
        } else {
           $.loadLoguser();
        }
    };
    $.dispatch = function(params) {
        switch(params[keyKey]) {
            case 'loguser':
                current = 'loguser';
                $.loadLoguser(params);
                break;
            default:
                current = '';
                $.execute(hash.substring(2));
                break;
        }
    };
    $.execute = function(content) {
        alert(content);
    };
    $.loadLoguser = function(params) {
        if(!username) { alert("帐号已过期！"); }
        var urlstr = 'portal/log.php?username=' + username;
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
            width: 954,
            height: 360,
            resizable: false,
            autoload: false,
            title: '用户登录信息日志报表',
            procmsg: '加载中,请稍等...',
            nomsg: '没有信息', 
            pagestat: '显示 {from} ~ {to} 共{total} '
        };
        if(params && params[newpKey]) { grid.newp = parseInt(params[newpKey],10); }
        if(params && params[rpKey]) { grid.rp = parseInt(params[rpKey],10); }
        $("#flex").flexigrid(grid);
        $("#flex").flexReload();
    };
    
    $(window).hashchange(function() {
        hash = document.location.hash;
        $.inithash();
    });

    $.inithash();
    $(".body_menu").hide();
    $(".body_right").css({"width":"954px"});
});
