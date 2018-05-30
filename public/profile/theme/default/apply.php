<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>申请</title>
<script type="text/javascript" src="/lib/underscore.js"></script>
<script type="text/javascript" src="/cache/js/profile.lib.js"></script>
<style type="text/css">
	body {
		margin: 0px;
		padding: 20px;
		font-size: 12px;
	}
	h2 {
		padding: 0;
		margin: 0 0 20px 0;
	}
	img {
		margin: 0;
		padding: 0;
		border: 0;
	}
	p {
		margin: 12px 0;
	}
	p span, p a {
		display: inline-block;
	}
	p span.colon {
		width: 10px;
	}
	p span.title {
		font-weight: bolder;
		font-size: 14px;
		width: 100px;
	}
	.title {
		display: inline-block;
	}
	h2 span {
		height: 24px;
		font-size: 24px;
		line-height: 24px;
	}
	.loading-container {
		height: 24px;
		margin: 0 0 0 12px;
		display: inline-block;
	}
	.loading {
		height: 22px;
	}
	.hide {
		display: none;
	}
	.show {
		display: block;
	}
	p.submit-container {
		margin: 12px 0 0 0;
	}
</style>
</head>
<body>
<div class="main">
  	<div class="header">
		<h2><span class="title">申请</span><span class="loading-container loader hide"><img class="loading" src="/images/loading.1.gif"></span></h2>
		<?php if(!empty($user)) {?>
		<p><span><?php echo $user['username'];?> 欢迎您!</span><a href="logout.php">&nbsp;退出</a></p>
		<input type="hidden" id="uid" value="<?php echo $user['uid']?>">
		<?php }?>
	</div>
  	<div>
  		<p><span class="title">应用ID</span><span class="colon">:</span><span><input name="clientId"></span></p>
  		<p><span class="title">应用名称</span><span class="colon">:</span><span><input name="clientName"></span></p>
  		<p><span class="title">应用类型</span><span class="colon">:</span><span><input name="clientType"></span></p>
  		<p><span class="title">回调地址</span><span class="colon">:</span><span><input name="redirectURI"></span></p>
  		<p><span class="title">应用地址</span><span class="colon">:</span><span><input name="clientLocation"></span></p>
  		<p><span class="title">LOGO地址</span><span class="colon">:</span><span><input name="clientLogoUri"></span></p>
  		<p><span class="title">描述</span><span class="colon">:</span><span><input name="clientDescription"></span></p>
  		<p class="submit-container"><input type="submit" value="提交"></p>
  	</div>
</div>
</body>
</html>

