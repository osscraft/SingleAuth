
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $LANG['PORTAL_TITLE']?></title>
<meta name="viewport" content="width=device-width">
<link type="text/css" rel="stylesheet" href="/cache/css/portal.fix.css" />
<link rel="stylesheet" type="text/css" href="/lib/font-awesome/css/font-awesome.css">
<!-- <script type="text/javascript" src="/cache/js/jquery.js"></script> -->
<script type="text/javascript" src="/lib/underscore.js"></script>
<script type="text/javascript" src="/cache/js/portal.lib.js"></script>
<script type="text/javascript" src="/cache/js/portal.fix.js"></script>
<!-- <script type="text/javascript" src="/js/theme.js"></script> -->
<script type="text/javascript">
<?php if(!empty($CFG["tranditional_apps"])) {?>
Portal.trans = <?php echo $CFG["tranditional_apps"]?>;
<?php }?>
</script>
</style>
</head>
<body>
<!--<a class="skins_link theme mains"><span><i class="fa fa-tachometer fa-tachometer-md"></i>&nbsp;<span></span></span></a>-->
<a class="skins_link theme mains  <?php if($CFG['theme_customize'])echo 'show';?>"><span><i class="fa fa-tachometer fa-tachometer-md"></i></span><div id="themes"></div></a>
<div class="global_header">
	<div class="header_inner">
		<div class="header_wrapper">
			<div class="logo_container">
				<a class="logo_link" href="/" title="<?php echo $LANG['PORTAL_HOMEPAGE']?>">
					<img class="logo_img" src="<?php echo $CFG['logo_portal_url'];?>" height="40px" border="0"/>
				</a>
			</div>
			<div class="nav_list_container">
				<ul class="nav_list">
					<li class="nav_item"><a class="nav_link" href="/dev/wiki.php" target="_blank"><?php echo $LANG['PORTAL_WIKI']?></a></li>
					<li class="nav_item"><a class="nav_link" href="/dev/support.php" target="_blank"><?php echo $LANG['PORTAL_SUPPORT']?></a></li> 
					<li class="nav_item"><a class="nav_link" href="/docs/EndUserSSO-Help/html/EndUserSSO-Help.xml.html" target="_blank"><?php echo $LANG['PORTAL_HELP']?></a></li>
				</ul>
			</div>
			<div class="pull_right">
                <div class="login_link_container login">
                	<span id="ssologin_btn">
						<a class="login_link"><?php echo $LANG['PORTAL_SIGNIN']?></a>
					</span>
				</div>
				<div class="login_link_container loging" style="display:none;">
					<span id="ssologing_btn"><?php echo $LANG['PORTAL_SIGNING']?>...</span>
				</div>
				<div class="login_link_container logout" style="display:none;">
					<span id="ssousername_btn" class="login_username"><span class="btn_center"></span></span>
					<span id="ssolog_btn"><a class="login_link" href="log.php" target="_blank"><?php echo $LANG['PORTAL_LOG']?></a></span>
					<span id="ssologout_btn"><a class="login_link" href="javascript:;"><?php echo $LANG['PORTAL_SIGNOUT']?></a></span>
				</div>
            </div>
        </div>
		
	</div>
</div>
<div class="total_ontainer">
	<!--<div id="themes">
		
	</div>-->
	<div class="list_display">
		<div class="inner">
			<div id="switcher" class="switcher-container">
				<ul>
					
					<li class="switcher-item loader-container"><div class="switcher loading-container loader hide"><img src="/images/loading.1.gif" class="loading"/></div></li>
					<li class="switcher-item"><a class="teacher switcher" tab="teacher" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['TEACHER']?></a></li>
					<li class="switcher-item"><a class="student switcher" tab="student" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['STUDENT']?></a></li>
					<li class="switcher-item hide"><a class="other switcher" tab="other" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['OTHER']?></a></li>
					<!-- <li class="switcher-item hide"><a class="statistics switcher" tab="statistics" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['PORTAL_LOG']?></a></li> -->
					<li class="switcher-item hide"><a class="election switcher" tab="election" onclick="javascript:Portal.clickTab(this)">自定义</a></li>
				</ul>
			</div>
			<div id="ssologin" class="item apps first">
				<div class="item_header">
					<h3 class="item_title">
						<span class="item_icon"></span>
						<span class="main_title"><?php echo $LANG['PORTAL_SSO_SIGNIN']?></span>
					</h3>
				</div>
				<!--<div id="divPageLoading" class="loading-container"><img src="/images/loading.gif" class="loading"/></div>-->
				<div class="case_exhibit" id="appsbysso">
			</div>
			<div id="tranlogin"  class="item apps last">
				<div class="item_header">
					<h3 class="item_title">
						<span class="item_icon"></span>
						<span class="main_title"><?php echo $LANG['PORTAL_TRANDITIONAL_SIGNIN']?></span>
					</h3>
				</div>
				<div class="case_exhibit" id="appsbytran">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="op_footer">			
	<div class="inner">
		<p class="links">
			<a class="foot_link" href="/" target="_blank"><?php echo $LANG['PORTAL_HOMEPAGE']?></a><span>|</span>
			<a class="foot_link" href="/dev" target="_blank"><?php echo $LANG['PORTAL_ABOUT']?></a><span>|</span>
			<a class="foot_link" href="/dev/support.php#other" target="_blank"><?php echo $LANG['PORTAL_CONTACT']?></a>
		</p>
		<p class="copyright">
			<span><a href="http://www.dcux.com" target="_blank"><?php echo $LANG['PORTAL_DCUX']?></a> <?php echo $LANG['PORTAL_DCUX_SUPPORT']?></span>
			<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<span>©Copyright <?php echo $LANG['PORTAL_START_YEAR']?>-<?php echo $LANG['CURRENT_YEAR']?> <?php echo $LANG['PORTAL_YOUR_NAME']?></span>
		</p>
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