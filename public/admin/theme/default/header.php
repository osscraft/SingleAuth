<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="zh-cn" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="zh-cn" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="zh-cn" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="zh-cn" ng-app> <!--<![endif]-->
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!--<link href="../css/style.css" rel="stylesheet" type="text/css" />-->
  <link href="/cache/css/authorize.css" rel="stylesheet" type="text/css" />
  <title><?php echo $TITLE;?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" type="text/css" href="/lib/flexigrid-1.1/css/flexigrid.css"/>
  <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/cache/js/excanvas.js"></script><![endif]-->
  <script type="text/javascript" src="/lib/underscore.js"></script>
  <script type="text/javascript" src="/cache/js/adminlib.js"></script>
  <script type="text/javascript" src="/cache/js/admin.js"></script>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="block_header">
      <div class="logo"><a href="index.php"><img src="../images/logo.png" border="0" alt="logo" /></a></div>
      <div class="search" style="text-align:right;">
        <!-- IF SESSION.user.username -->
        <?php if(!empty($SESSION['user'])) {?>
        <p><?php echo $SESSION['user']['username'];?> <?php echo $LANG['HELLO_RIGHT'];?> <a href="logout.php"><?php echo $LANG['LOGOUT'];?></a></p>
        <!-- ELSE -->
        <?php } else {?>
	    <!-- 授权按钮 -->
        <p><a href="<?php echo $URL;?>"><img src="../images/login_24.png" title="点击进入授权页面" alt="点击进入授权页面" border="0"/></a></p>
        <!-- ENDIF -->
        <?php }?>
      </div>
      <div class="menu">
        <ul>
          <!-- IF SIGN == "index" -->
          <?php if($SIGN == "index") {?>
          <li><a href="index.php" class="active"><span><?php echo $LANG['INDEX'];?></span></a></li>
          <!-- ELSE -->
          <?php } else {?>
          <li><a href="index.php"><span><?php echo $LANG['INDEX'];?></span></a></li>
          <!-- ENDIF -->
          <?php }?>
          <!-- IF SIGN == "client" -->
          <?php if($SIGN == "client") {?>
          <li><a href="client.php" class="active"><span><?php echo $LANG['CLIENT_MANAGER'];?></span></a></li>
          <!-- ELSE -->
          <?php } else {?>
          <li><a href="client.php"><span><?php echo $LANG['CLIENT_MANAGER'];?></span></a></li>
          <!-- ENDIF -->
          <?php }?>
          <!-- IF SIGN == "ldapConfig" -->
          <?php if($SIGN == "setting") {?>
          <li><a href="setting.php" class="active"><span><?php echo $LANG['SETTING_MANAGER'];?></span></a></li>
          <!-- ELSE -->
          <?php } else {?>
          <li><a href="setting.php"><span><?php echo $LANG['SETTING_MANAGER'];?></span></a></li>
          <!-- ENDIF -->
          <?php }?>
          <!-- IF SIGN == "authInfo" -->
          <?php if($SIGN == "stat") {?>
          <li><a href="stat.php" class="active"><span><?php echo $LANG['STATISTICS'];?></span></a></li>
          <!-- ELSE -->
          <?php } else {?>
          <li><a href="stat.php"><span><?php echo $LANG['STATISTICS'];?></span></a></li>
          <!-- ENDIF -->
          <?php }?>
          <!-- IF SIGN == "user" -->
          <?php if($SIGN == "user") {?>
          <li><a href="user.php" class="active"><span><?php echo $LANG['USER_MANAGER'];?></span></a></li>
          <!-- ELSE -->
          <?php } else {?>
          <li><a href="user.php"><span><?php echo $LANG['USER_MANAGER'];?></span></a></li>
          <!-- ENDIF -->
          <?php }?>
		   <!-- IF SIGN == "skin" -->
          <?php if($SIGN == "skin") {?>
          <li><a href="skin.php" class="active"><span>皮肤管理</span></a></li>
          <?php } else {?>
          <li><a href="skin.php"><span>皮肤管理</span></a></li>
          <?php }?>
        </ul>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="slider_top">
    <div class="header_text2">
      <!--<a href="#"><img src="../images/Sing_up.gif" alt="picture" border="0" /></a>-->
      <h2>
        <?php if($SIGN == "index") {?><?php echo $LANG['INDEX'];?>
        <?php } else if($SIGN == "client") {?><?php echo $LANG['CLIENT_MANAGER'];?>
        <?php } else if($SIGN == "setting") {?><?php echo $LANG['SETTING_MANAGER'];?>
        <?php } else if($SIGN == "stat") {?><?php echo $LANG['STATISTICS'];?>
        <?php } else if($SIGN == "user") {?><?php echo $LANG['USER_MANAGER'];?>
		<?php } else if($SIGN == "skin") {?>皮肤管理
        <?php } else if($SIGN == "invalid_user") {?><?php echo $LANG['INVALID_USER'];?>
        <?php } else if($SIGN == "invalid_access_token") {?><?php echo $LANG['INVALID_TOKEN'];?>
        <?php } else if($SIGN == "unkwown_error") {?>Error
        <?php }?>
      </h2>
      <p><!-- IF SIGN == "client" -->
        <?php if($SIGN == "client") {?>
        <a href="client.php?key=tocreate">新增客户端</a>
        <!-- ELSEIF SIGN == "user" -->
          <?php } else if($SIGN == "user") {?>
        <a href="user.php?key=tocreate">新增用户</a>
		<!-- ELSEIF SIGN == "setting" -->
          <?php } else if($SIGN == "setting") {?>
        <a href="setting.php?key=tocreate">新增配置</a>
        <!-- ENDIF -->
          <?php }?>
      </p>
      <div class="clr"></div>
    </div>
  </div>
  <div class="top_bg2">
   <div class="clr"></div>
  </div>
  <div class="clr"></div>
