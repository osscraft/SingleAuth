<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>密码维护</title>
 <script type="text/javascript" src="/cache/js/adminlib.js"></script>
 <script type="text/javascript" src="js/ChangePass.js"></script>
 <!--<link rel="stylesheet" type="text/css" href="/lib/flexigrid-1.1/css/flexigrid.css"/>-->
 <style>
.password{ 
	display: block; 
	width: 100%; 
	line-height: 1.428571429; 
	color: #555555; 
	vertical-align: middle; 
	background-color: #ffffff; 
	border: 1px solid #cccccc; 
	border-radius: 4px; 
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075); 
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075); 
	-webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; 
	transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; 
} 
.password:focus { 
	border-color: #66afe9; 
	outline: 0; 
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, 0.6); 
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, 0.6); 
} 
.button,.button:visited {
	background: #222 url(/images/overlay.png) repeat-x;
	display: inline-block; 
	padding: 5px 10px 6px; 
	text-decoration: none;
	-moz-border-radius: 6px; 
	-webkit-border-radius: 6px;
	-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
	-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
	<!--text-shadow: 0 -1px 1px rgba(0,0,0,0.25);-->
	border-bottom: 1px solid rgba(0,0,0,0.25);
	position: relative;
	cursor: pointer;
	font-size: 13px; 
	font-weight: bold; 
	line-height: 1; 
}
.button:hover{
	background-color: #111;
}
.submit.button, .submit.button:visited		{ background-color: #e1ecef; }
.submit.button:hover						{ background-color: #e6e6e6; }
.button:active							{ top: 1px; }
.mainPage{
	margin:5px 0 0px 15px;
}
 </style>
</head>
<body>
<div class="main"style="width:100%; padding:0; margin:0 auto;overflow:hidden;">
  <div class="header" style="background:#e1ecef; padding:0; margin:0 auto;">
    <div class="block_header" style="margin:0 auto; padding:0">
		<p style="float:left;font-size:25px;font-weight:bold;margin-top:5px;margin-bottom:5px;margin-left:50px;">认证开放平台密码维护</p>
		<!-- IF SESSION.user.username -->
		<?php if(!empty($_SESSION['cp_user'])) {?>
		<p style="float:right;margin-top:15px;margin-bottom:-10px;margin-right:30px;"><?php echo $_SESSION['cp_user']['username'];?> 欢迎您!<a href="logout.php">&nbsp;退出</a></p>
		<input type="hidden" id="uid" value="<?php echo $_SESSION['cp_user']['uid']?>">
		<!-- ELSE -->
		<?php } else {?>
		<!-- 授权按钮 -->
		<p style="float:right;margin-top:8px;margin-bottom:-10px;"><a href="<?php echo $URL;?>"><img src="../images/login_24.png" title="点击进入授权页面" alt="点击进入授权页面" border="0"/></a></p>
		<!-- ENDIF -->
		<?php }?>
		<div class="clr" style="clear:both;"></div>
    </div>
	  
   </div>
   <div>
	<div class="left" style="margin:2px 2px 2px 0;width:24%;border:2px solid #e1ecef;float:left;position:absolute;bottom:2px;top:50px;">
		<p style="width:100%;height:30px;background:#e1ecef;margin-top:0;text-align:center;padding-top:5px;"><b>功能列表<b></p>
		<ul style="list-style-type:none;">
			<li style="height:30px;"><a href="javascript:" onclick="$('#updform').attr('style','display:none');$('.mainPage').attr('style','display:true');"><span>主页</span></a></li>
			<li><a href="javascript:" onclick="$('#updform').attr('style','display:true;');$('.mainPage').attr('style','display:none');"><span>修改密码</span></a></li>
		</ul>
	</div>
	<div class="right" style="margin:2px 2px 2px 2px;border:2px solid #e1ecef;float:right;position:absolute;bottom:2px;top:50px;right:4px;left:25%;">
		<div class="mainPage" style="margin:5px 0 0px 15px;"><p><h2>密码维护页面</h2></p></div>
		<div id="updform" style="display:none;">
			<form id="upd" method="post" action="index.php" style="margin:5px 0 0px 15px;">
				<legend><p style="font-weight:bold;"><font size='5'>修改密码</font>&nbsp;&nbsp;&nbsp;&nbsp;<span id="msg" style="color:red"></span></p></legend>
				<!--<div style="margin-bottom:8px;">
					<div style="float:left"><label>原始密码</label></div>
					<div style="float:right;margin-right:490px;width:200px;"><span id="omsg" style="color:red;">&nbsp;</span></div>
					<div style="float:right;margin-right:10px;">
						<input type="password" name="opassword" id="opassword" class="password">
					</div>
					<div class="clr" style="clear:both;"></div>
				</div>-->
				
				<div style="margin-bottom:8px;">
					<div style="float:left;width:18%;"><label>原始密码</label></div>
					<div style="float:left;margin-left:4%;margin-bottom:3px;">
						<input type="password" name="opassword" id="opassword" class="password">
					</div>
					<div style="float:left;margin-left:4%;width:35%;"><span id="omsg" style="color:red;">&nbsp;</span></div>
					<div class="clr" style="clear:both;"></div>
				</div>
				<!--<div style="margin-bottom:8px;">
					<div style="float:left"><label>新密码</label></div>
					<div style="float:right;margin-right:490px;width:200px;"><span id="nmsg" style="color:red">&nbsp;</span></div>
					<div style="float:right;margin-right:10px;"><input type="password" name="npassword" id="npassword" class="password"/></div>
					<div class="clr" style="clear:both;"></div>
				</div>-->
				<div style="margin-bottom:8px;">
					<div style="float:left;width:18%;"><label>新密码</label></div>
					<div style="float:left;margin-left:4%;"><input type="password" name="npassword" id="npassword" class="password"/></div>
					<div style="float:left;margin-left:4%;width:35%;"><span id="nmsg" style="color:red">&nbsp;</span></div>
					<div class="clr" style="clear:both;"></div>
				</div>
				
				<!--<div style="margin-bottom:8px;">
					<div style="float:left"><label>确认密码</label></div>
					<div style="float:right;margin-right:490px;width:200px;"><span id="cmsg" style="color:red">&nbsp;</span></div>
					<div style="float:right;margin-right:10px;"><input type="password" name="cpassword" id="cpassword" class="password"/></div>
					<div class="clr" style="clear:both;"></div>
				</div>-->
				
				<div style="margin-bottom:8px;">
					<div style="float:left;width:18%;"><label>确认密码</label></div>
					<div style="float:left;margin-left:4%;"><input type="password" name="cpassword" id="cpassword" class="password"/></div>
					<div style="float:left;margin-left:4%;width:35%;"><span id="cmsg" style="color:red">&nbsp;</span></div>
					
					<div class="clr" style="clear:both;"></div>
				</div>
				
				<div>
					<a class="submit button">确认修改</a>
				</div>
			</form>
		</div>
	</div>
   </div>
</div>
</body>
</html>

