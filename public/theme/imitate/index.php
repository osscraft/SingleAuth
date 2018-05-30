<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $LANG['PORTAL_TITLE']?></title>
<link rel="stylesheet" type="text/css" href="/cache/css/portal.imitate.css"/>
<link rel="stylesheet" type="text/css" href="/lib/font-awesome/css/font-awesome.css">
<script type="text/javascript" src="/lib/underscore.js"></script>
<script type="text/javascript" src="/cache/js/portal.lib.js"></script>
<script type="text/javascript" src="/cache/js/portal.imitate.js"></script>
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
    <div class="ls_head"></div>
      <div class="logo"><a href="/"><img src="<?php echo $CFG['logo_portal_url'];?>"  alt="logo" border="0" /></a>
    </div>
    <div class="ls_info">
      <!-- <a href="" target="_blank" ><span>登录</span></a> -->
      <span class="ssologin">
        <span id="ssologin_btn" class="ssologin">
          <a style="width:80px;"><?php echo $LANG['PORTAL_SIGNIN']?></a>
        </span>
      </span>
      <span class="ssologin">
        <span id="ssologing_btn" class="ssologing">
          <span><?php echo $LANG['PORTAL_SIGNING']?>...</span>
        </span>
      </span>
      <span class="ssologin">
        <span id="ssousername_btn" class="ssousername">
          <span class="btn_center"></span>,
        </span>
      </span>
      <span class="ssologin">
        <span id="ssologout_btn" class="ssologout">
          <a><?php echo $LANG['PORTAL_SIGNOUT']?></a>
        </span>
      </span>
      &nbsp;|&nbsp;
      <a href="log.html" target="_blank"><img src="images/logtext.png"/><span><?php echo $LANG['PORTAL_LOG']?></span></a>
      &nbsp;|&nbsp;
      <a href="docs/EndUserSSO-Help/html/EndUserSSO-Help.xml.html" target="_blank"><img src="images/help.png"/><span><?php echo $LANG['PORTAL_HELP']?></span></a>
    </div>
  </div>
  <div class="clr"></div>
  <a class="skins_link theme mains  <?php if($CFG['theme_customize'])echo 'show';?>"><span><i class="fa fa-tachometer fa-tachometer-md"></i></span><div id="themes"></div></a>
	<div class="clr"></div>
	<!--</span><div id="themes"></div>-->
 </div>
 <input id="app_columns" type="hidden" value="8"/>
 <div id="sso">
  <div class="banner"> 
    <span class="title"><?php echo $LANG['PORTAL_SSO_SIGNIN']?></span><span class="extra"></span>
  </div>
  <div class="app" id="appsbysso"></div>
  <div class="clr"></div>
 </div>

 <div id="nosso">
  <div class="banner">
    <span class="title"><?php echo $LANG['PORTAL_TRANDITIONAL_SIGNIN']?></span><span class="extra"></span>
  </div>
  <div class="app" id="appsbytrans"></div>
  <div class="clr"></div>
 </div>

 <div class="bottom">
    <p><?php echo $LANG['PORTAL_YOUR_NAME']?></p>
    <p><?php echo $LANG['PORTAL_YOUR_ADDRESS']?></p>
    <p>©Copyright <?php echo $LANG['PORTAL_START_YEAR']?>-<?php echo $LANG['CURRENT_YEAR']?> <?php echo $LANG['PORTAL_YOUR_NAME']?> <?php echo $LANG['PORTAL_YOUR_ICP']?></p>
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
</html>
