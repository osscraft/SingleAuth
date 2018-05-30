
var Authorize = {};

Authorize.init = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    //验证码
    if(App.Util.exists('#getcode_char')) {
        $("#getcode_char").attr("src", "verifyCode.php?key=verifyCode&_dr=" + Math.random() );
    }
    if(App.Util.exists('.skip-delay')) {
        var delay = parseInt($('#skip_delay').val());
        var url = $('#skip_url').val();
		var options = $("#client_id");
        Authorize.delay(delay, url);
    }
    // placeholder
    $('input, textarea').placeholder();
};
Authorize.listen = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    if(App.Util.exists('#code_char')) {
        //验证码
        $("#code_char").blur(function(){
            var code_char = $("#code_char").val();
            
                code_char = code_char.toLowerCase();
                //url,content,data
                $.post("verifyCode.php?act=char&key=checkCode",{"verifyCode":code_char},function(msg){
                    var json = eval(msg);
                    if(json && json.success == "1"){
                        $("#user_info").css({"color":"green"});
                        $("#verifyCodeSuccess").val("1");
                        $("#user_info").html("验证码正确！");
                    }else{
                        $("#user_info").css({"color":"red"});
                        $("#verifyCodeSuccess").val("0");
                        $("#user_info").html("验证码错误！");
                    }
                });
        });
    }
    if(App.Util.exists('#getcode_char')) {
        //获取验证码图片
        $("#getcode_char").click(function(){
            $(this).attr("src", "verifyCode.php?key=verifyCode&_dr=" + Math.random() );
            return false;
        });
    }
    // 用户名
    $("#username").blur(function(){
        $username = $("#username").val();
        if($username == "") {
            $("#user_info").css({"color":"red"});
            $("#user_info").html("用户名不能为空！");
        } else {
            $("#user_info").html('&nbsp;');
        }
    });
    // 密码
    $("#password").blur(function(){
        $password = $("#password").val();
        if($password == "") {
            $("#user_info").css({"color":"red"});
            $("#user_info").html("密码不能为空！");
        } else {
            $("#user_info").html('&nbsp;');
        }
    });
    // 其他帐号
    $('#other_account').click(function(){
        //onclick="javascript:document.getElementById('otherlogin').value=1;document.getElementsByTagName('form')[0].submit();"
        $('#otherlogin').val('1');
        $('form').submit();
    });
    // 忘记密码
    $('#forgot_password').click(function(){
        clickForgotPassword(this);
    });
    // 易班登录
    $('#login_by_yb').click(function(){
        loginByYB(this);
    });
};
Authorize.delay = function(delay, url, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.per =  _.isUndefined(option.per) ? 500 : option.per;

    if(delay > 0) {
        setTimeout(function() {
            $('#skip_delay_seconds').html(Math.floor(delay/1000));
            delay -= option.per;//
            Authorize.delay(delay, url, option);
        }, option.per);
    } else if(url) {
        window.location = url;
    } else {
		$("#login-form").submit();
	}
};

$(document).ready(function(){
    Authorize.init();
    Authorize.listen();
});

function checkUsernameAndPassword(){
    $username = $("#username").val();
    $password = $("#password").val();
    if($username == "" || $password == ""){
        $("#user_info").css({"color":"red"});
        if($username == "" ){
            $("#user_info").html("用户名不能为空！");
        }
        if($password == "" ){
            $("#user_info").html("密码不能为空！");
        }
        if($username == "" && $password == "" ){
            $("#user_info").html("用户名和密码不能为空！");
        }
        return false;
    } else{
        return true;
    }
}
/*function checkUsername() {
    $username = $("#username").val();
    if($username == "") {
        $("#user_info").css({"color":"red"});
        $("#user_info").html("用户名不能为空！");
    } else {
        $("#user_info").html('&nbsp;');
    }
}
function checkPassword() {
    $password = $("#password").val();
    if($password == "") {
        $("#user_info").css({"color":"red"});
        $("#user_info").html("密码不能为空！");
    } else {
        $("#user_info").html('&nbsp;');
    }
}*/

function validateUsernameAndPassword(){
    $username = $("#username").val();
    $password = $("#password").val();
	//console.log($password);
    if($username == "" || $password == ""){
        if($username == "" && $password == "" ){
            $("#user_info").html("用户名和密码不能为空！");
            alert("用户名和密码不能为空！");
        } else if($username == "" ){
            $("#user_info").html("用户名不能为空！");
            alert("用户名不能为空！");
        } else if($password == "" ){
            $("#user_info").html("密码不能为空！");
            alert("密码不能为空！");
        }
        return false;
    } else if( $("#getcode_char").attr("src") != null ){
        if( $("#verifyCodeSuccess").val() != "1"){
            alert("验证码错误！");
            return false;
        }
    } else{
        return true;
    }

}

function clickForgotPassword(el) {
    var href = $(el).attr('data-href');
    if(href) {
        window.location.href=href;
    }
}
function loginByYB(el) {
    window.location.href = config.baseURL + "/yb.php?rawurl=" + encodeURIComponent(window.location.href);
}


Authorize.ws;
Authorize.ws_timeid;
Authorize.ws_timeout;
Authorize.ws_flag = false;
Authorize.ws_expires = config.ws_expires;//60秒
Authorize.ws_init = function() {
    Authorize.ws.onopen = function() {
        console.log('open');
        Authorize.ws_update();
        Authorize.ws_timeid = setInterval(Authorize.ws_update, config.ws_expires);
    }
    Authorize.ws.onmessage = function(e) {
        param = JSON.parse(e.data);
        console.log(param);
        if(param['cmd'] == 'ping'){
            Authorize.ws.send(JSON.stringify({"cmd":"pong"}));
        } else if(param['cmd']=='/qr/req'){
            var qrsrc = '/cache/image/qrlogin.png?cid=' + param['data']['cid'] + '&scode=' + encodeURIComponent(param['data']['scode']);
            //$.cookie('scode',param['data']['scode']);
            //$.cookie('cid',param['data']['cid']);
            $('#code').attr('src', '/images/loading.1.gif');
            /*if(Util.isCanvasSupported()) {
                Util.getBase64FromImageUrl(qrsrc, function(data) {
                    $('#code').attr('src', data);
                });
            } else {*/
            $('#code').attr('src', qrsrc);
            //}
        } else if(param['cmd'] == '/qr/scan'){
            Authorize.ws_timeid && clearInterval(Authorize.ws_timeid);
            Authorize.ws_flag=true;
            $('#scaned').show();
            $('#code').hide();
            Authorize.ws_timeout = setTimeout(window.location.reload,60000);
        } else if(param['cmd'] == '/qr/login'){
            var sid = param['data']['sid'];
            var fcid = param['data']['fcid'];
            Authorize.ws_timeid && clearInterval(Authorize.ws_timeid);
            if(Authorize.ws_flag){
                clearTimeout(Authorize.ws_timeout);
                var referer = $('#referer').val();
                var action = $('#login-form').attr('action');
                var url = action + "&qr=1&referer="+encodeURIComponent(referer)+"&sid="+encodeURIComponent(sid)+"&cid="+$.cookie('cid')+"&fcid="+fcid;
                window.location.href=url;
            }
        }
    };
    Authorize.ws.onclose = function() {
        console.log("连接关闭");
    };
    Authorize.ws.onerror = function() {
        console.log("出现错误");
    };
    Authorize.ws_update = function(){
        Authorize.ws.send(JSON.stringify({"cmd":"/qr/req"}));
    };
    window.onbeforeunload=function(){
        Authorize.ws.close();
    };
};

$(function(){
    if(!App.Util.exists('#qrlogin')) {
        return;
    }
    var clicked=false;
    if(WebSocket.loadFlashPolicyFile) {
        var url = location.protocol + '//' + location.host + '/lib/web-socket-js/crossdomain.xml';//xmlsocket:
        //WebSocket.loadFlashPolicyFile(url);
        //WebSocket.loadFlashPolicyFile(location.host + '/lib/web-socket-js/crossdomain.xml');//location.protocol + 
    }
    $('#qrlogin').click(function(){
		if(!Authorize.checkBlowser()){
			alert("IE浏览器版本过低，请升至IE8及以上版本");	
		}else{
			clicked=!clicked;
			if(Authorize.ws == undefined){
				Authorize.ws = new WebSocket(config.ws_uri);
				Authorize.ws_init();
			}
			if(Authorize.ws_flag){
				$('#scaned').show();
				$('#code').hide();
			}else{
				$('#code').show();
				$('#scaned').hide();            
			}
			if(clicked){    
				$('#qrcode').show();
				$('#qrrefresh').show();
			}else{
				$('#qrcode').hide();
				$('#qrrefresh').hide();
			}
        }
    });
    $('#qrrefresh').click(function(){
        if(Authorize.ws_flag) {
            $('#code').show();
            $('#scaned').hide();
			Authorize.ws_flag = ! Authorize.ws_flag;
        }
        Authorize.ws_update();
    });
}); 
Authorize.checkBlowser = function(){
	if($.browser.msie){
		return $.browser.version>=8.0;
	}
	return true;
}
Authorize.login = function(){
	$("#login-form").submit();
}