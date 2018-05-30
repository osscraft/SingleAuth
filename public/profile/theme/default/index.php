<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户个人信息</title>
<style type="text/css">
	p span, p a {
		display: inline-block;
	}
	p span.colon {
		width: 10px;
	}
	p span.title {
		width: 100px;
	}
</style>
</head>
<body>
<div class="main">
  	<div class="header">
		<h2>用户个人信息</h2>
		<?php if(!empty($user)) {?>
		<p><span><?php echo $user['username'];?> 欢迎您!</span><a href="logout.php">&nbsp;退出</a></p>
		<input type="hidden" id="uid" value="<?php echo $user['uid']?>">
		<?php } else {?>
		<p><a href="<?php echo empty($URL) ? '' : $URL;?>"><img src="/images/login_24.png" title="点击进入授权页面" alt="点击进入授权页面" border="0"/></a></p>
		<?php }?>
	</div>
  	<div>
		<?php if(!empty($user)) {?>
  		<p><span class="title">UID</span><span class="colon">:</span><span><?php echo empty($user) ? '' : $user['uid'];?></span></p>
  		<p><span class="title">USERNAME</span><span class="colon">:</span><span><?php echo empty($user) ? '' : $user['username'];?></span></p>
  		<p><span class="title">ROLE</span><span class="colon">:</span><span><?php echo empty($user) ? '' : $user['role'];?></span></p>
  		<?php }?>
  	</div>
</div>
</body>
</html>

