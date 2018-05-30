<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $LANG['PORTAL_TITLE']?></title>
<meta name="viewport" content="width=device-width">
<script type="text/javascript" src="cache/js/authorizelib.js"></script>
<script type="text/javascript" src="cache/js/authorize.js"></script>
<script type="text/javascript" src="/lib/web-socket-js/swfobject.js"></script>
<script type="text/javascript" src="/lib/web-socket-js/web_socket.js"></script>
<script text="text/javascript">
	var timeid,timeout;
	var flag=false;
	// Let the library know where WebSocketMain.swf is:
    WEB_SOCKET_SWF_LOCATION = "/lib/web-socket-js/WebSocketMain.swf";
	if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
	var ws = new WebSocket(config.ws_uri);
	function init() {
		ws.onopen = function() {
			console.log('open');
			update();
			timeid = setInterval(update,30000);
		}
		ws.onmessage = function(e) {
			param = JSON.parse(e.data);
			if(param['cmd'] == 'ping'){
				ws.send(JSON.stringify({"cmd":"pong"}));
			} else if(param['cmd']=='req'){
				$.cookie('qrtime',param['data']['qrtime']);
				$.cookie('cid',param['data']['cid']);
				Util.getBase64FromImageUrl('http://sso.project.dcux.com/cache/image/qrlogin.png?qrtime=1444982354&cid=49', function(data) {
					console.log(data);
					$('#code').attr('src',data);
				});
				//$('#code').attr('src','/cache/image/qrlogin.png?qrtime='+$.cookie('qrtime')+'&cid='+$.cookie('qrcode'));
			} else if(param['cmd'] == 'res'){
				clearInterval(timeid);
				if(param['data']['cmd']=='scan'){
					flag=true;
					$('#qrcode').hide();
					$('#scaned').show();
					timeout = setTimeout(myrefresh,60000);
				}else{
					if(flag){
						clearTimeout(timeout);
						window.location.href="index.php";
					}
				}
			}
		};
		ws.onclose = function() {
			console.log("连接关闭");
		};
		ws.onerror = function() {
			console.log("出现错误");
		};
	}
	window.onbeforeunload=function(){
		ws.close();
	}
	function myrefresh() { 
       	window.location.reload(); 
	}
	function update(){
		ws.send(JSON.stringify({"cmd":"req"}));
	}	
	
	$(function(){
		init();
	});	
</script>
</head>
<body>
	<div style="text-align:center;">
		<h1>二维码登录</h1>
		
		<div id="qrcode">
			<input type="hidden" value="<?php echo $uid;?>" />
			<img id="code"/>
		</div>
		<h1 id='scaned' style="display:none;">已扫描，请在手机端确认登录</h1>
	</div>
	<div id="msg"></div>
</body>
</html>
