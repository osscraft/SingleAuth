<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $LANG['PORTAL_TITLE']?></title>
<!--link href="css/portal.1.css" rel="stylesheet" type="text/css" />-->
<link href="cache/css/index.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/lib/font-awesome/css/font-awesome.css">
<!--<script type="text/javascript" src="lib/jquery/jquery.js"></script>
<script type="text/javascript" src="lib/jquery/jquery.cookie.js"></script>
<script type="text/javascript" src="lib/jquery/jquery.observehashchange.js"></script>
<script type="text/javascript" src="lib/jquery/jquery.ba-bbq.js"></script>
<script type="text/javascript" src="lib/jquery/easySlider1.5.js"></script>-->
<script type="text/javascript" src="/lib/underscore.js"></script>
<script type="text/javascript" src="/cache/js/portal.lib.js"></script>
<!--<script type="text/javascript" src="js/env.js?v=3"></script>
<script type="text/javascript" src="js/config.index.js?v=3"></script>
<script type="text/javascript" src="js/SSOToOAuth2.js?v=3"></script>
<script type="text/javascript" src="js/portal.index.js?v=3"></script>-->
<script type="text/javascript" src="/cache/js/portal.js"></script>
<!-- <script type="text/javascript" src="/js/theme.js"></script> -->
<script type="text/javascript">
<?php if(!empty($CFG["tranditional_apps"])) {?>
Portal.trans = <?php echo $CFG["tranditional_apps"]?>;
<?php }?>
</script>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="header_block">
      <div class="logo"><a href="/"><img src="<?php echo $CFG['logo_portal_url'];?>"  border="0" alt="logo" /></a></div>
      <div class="info">
        <span class="info_right"></span>
        <!--<div id="appsoptions">-->
        <span class="info_block" id="ssohelp_btn"><a href="docs/EndUserSSO-Help/html/EndUserSSO-Help.xml.html" target="_blank"><span class="btn_center"><?php echo $LANG['PORTAL_HELP_INFO']?></span><span class="btn_icon" id="ssohelp_icon"></span></a></span>
        <!--<span class="info_block" id="ssologin_btn" style="width:120px;"><a><span class="btn_center" style="width:80px;">获取身份认证</span><span class="btn_icon" id="ssologin_icon"></span></a></span>
        <span class="info_block" id="ssologing_btn"><a><span class="btn_center">登录中...</span><span class="btn_icon" id="ssologing_icon"></span></a></span>
        <span class="info_block" id="ssologout_btn" style="width:80px;"><a><span class="btn_center" style="width:40px;">退出</span><span class="btn_icon" id="ssologout_icon"></span></a></span>-->
        <span class="info_block" id="ssolog_btn"><a href="log.html" target="_blank"><span class="btn_center"><?php echo $LANG['PORTAL_LOG_VIEW']?></span><span class="btn_icon" id="ssolog_icon"></span></a></span>
        <!--<span class="info_block" id="ssousername_btn"><a><span class="btn_center"></span><span class="btn_icon" id="ssousername_icon"></span></a></span>-->
          <!--<div class="clr"></div>-->
        <!--</div>-->
        <span class="info_left"></span>
        <div class="clr"></div>
      </div>
      <div class="clr"></div>
    </div>
	<a class="skins_link theme mains <?php if($CFG['theme_customize'])echo 'show';?>"><span><i class="fa fa-tachometer fa-tachometer-md"></i></span><div id="themes"></div></a>
	<div class="clr"></div>
	
  </div>
  <div class="nav">
    <div class="nav_block banner_block sso">
      <!--<span class="appsblank"></span>
      <span id="appsbysso_btn"><a ></a></span>
      <span class="appsblank2"></span>
      <span id="appsbytran_btn"><a ></a></span>-->
      <div class="ssologin_dv">
          <div id="ssologin_btn" class="ssologin">
            <a style="width:80px;"><?php echo $LANG['PORTAL_SIGNIN_GET']?></a>
          </div>
      </div>
      <div class="ssologin_dv">
          <div id="ssologing_btn" class="ssologing">
            <span><?php echo $LANG['PORTAL_SIGNING']?>...</span>
          </div>
      </div>
      <div class="ssologin_dv">
          <div id="ssousername_btn" class="ssousername" style="float:left;">
            <span class="btn_center"></span>
          </div>
          <!--<div id="ssolog_btn" class="ssolog" style="float:left;">
            <a href="log.html" target="_blank">查看日志</a>
          </div>-->
          <div id="ssologout_btn" class="ssologout" style="float:left;">
            <a><?php echo $LANG['PORTAL_SIGNOUT']?></a>
          </div>
          <div class="clr"></div>
      </div>
      <!-- <div class="clr"></div>-->
    </div>
  </div>
  <!--<div class="clr"></div>-->
  <div class="apps_bar">
    <div class="apps_block">
      <div id="slider">
        <ul>
          <li id="appsbysso">
          </li>
          <div class="clr"></div>
        </ul>
        <div class="clr"></div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="clr"></div>
  <div class="banner">
    <div class="banner_block nosso"></div>
  </div>
  <div class="apps_bar">
    <div class="apps_block">
      <div id="slider">
        <ul>
          <li id="appsbytrans">
          </li>
          <div class="clr"></div>
        </ul>
        <div class="clr"></div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="clr"></div>
  <div class="footer">
    <div class="footer_block">
      <div class="clr" style="height:240px;width:800px;"></div>
      <p class="leftt"><span><a href="http://www.dcux.com"><?php echo $LANG['PORTAL_DCUX']?></a>&nbsp;&nbsp;<?php echo $LANG['PORTAL_DCUX_SUPPORT']?></span>
      <p class="rightt"><span>©Copyright <?php echo $LANG['PORTAL_START_YEAR']?>-<?php echo $LANG['CURRENT_YEAR']?> <?php echo $LANG['PORTAL_YOUR_NAME']?></span></p>
      <div class="clr"></div>
    </div>
  </div>
</div>
<div id="popup-login">
  <form action="" method="post">
    <div class="popup-bg-top"></div>
    <div class="popup-bg-middle">
        <div class="popup-login-info"><?php echo $LANG['PORTAL_HAD_ACCOUNT']?>: <span id="logged-username"></span></div> 
        <div class="popup-login-action">
          <input type="hidden" name="loggedin" value="1">
          <input type="submit" name="accept" value="<?php echo $LANG['PORTAL_SIGNIN']?>">
        </div>
    </div>
    <div class="popup-bg-bottom"></div>
  </form>
</div>
<div id="popup-login-container">
</div>
</body>
<html>

