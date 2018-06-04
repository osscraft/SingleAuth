<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $LANG['PORTAL_TITLE']?></title>
<link rel="stylesheet" type="text/css" href="/cache/css/portal.detail.css"/>
<link rel="stylesheet" type="text/css" href="../lib/flexigrid-1.1/css/flexigrid.css"/>
<link rel="stylesheet" type="text/css" href="/lib/font-awesome/css/font-awesome.css">
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/cache/js/excanvas.js"></script><![endif]-->
<!-- <script type="text/javascript" src="/cache/js/jquery.js"></script>
<script type="text/javascript" src="/cache/js/adminlib1.js"></script> -->
<!--<script type="text/javascript" src="/cache/js/portal.js"></script>-->
<script type="text/javascript" src="/lib/underscore.js"></script>
<script type="text/javascript" src="/cache/js/portal.lib.js"></script>
<!-- <script type="text/javascript" src="/js/theme.js"></script> -->
<script type="text/javascript" src="/cache/js/portal.detail.js"></script>
<script type="text/javascript">
<?php if(!empty($CFG["tranditional_apps"])) {?>
Portal.trans = <?php echo $CFG["tranditional_apps"]?>;
<?php }?>
</script>
</head>
<body>
<div id="all">
<div class="main">
		<div id="head">
			<div class="header">
				<div class="header_block">
					<div class="logo"><a href="/"><img src="<?php echo $CFG['logo_portal_url'];?>" alt="logo" border="0"/></a></div>
					<div class="tab">
						<span class="ssologout">
							<span id="ssousername_btn" class="login_username"><span class="btn_center"></span></span>
							<span id="ssologout_btn"><a tab="logout" onclick="javascript:;"><?php echo $LANG['PORTAL_SIGNOUT']?></a>|</span>
							<!-- <span id="ssolog_btn"><a tab="statistics" onclick="Portal.clickTab(this)"><?php echo $LANG['PORTAL_LOG']?></a>|</span> -->
						</span>
						<span class="ssologin">
							<span id="ssologin_btn">
								<a tab="login"><?php echo $LANG['PORTAL_SIGNIN']?></a>|
							</span>
						</span>
						<!--<span class="switcher-container">
							<a tab="teacher" onclick="Portal.clickTab(this)"><?php echo $LANG['TEACHER']?></a>|
							<a tab="student" onclick="Portal.clickTab(this)"><?php echo $LANG['STUDENT']?></a>|
						</span>-->
						<span class="loging">
							<span id="ssologing_btn"><?php echo $LANG['PORTAL_SIGNING']?>...</span>
						</span>
						<a tab="help" href="docs/EndUserSSO-Help/html/EndUserSSO-Help.xml.html" target="_blank"><?php echo $LANG['PORTAL_HELP']?></a>
					</div>
					<div class="clr"></div>
				</div>
			</div>
			
			<a class="skins_link theme mains  <?php if($CFG['theme_customize'])echo 'show';?>"><span><i class="fa fa-tachometer fa-tachometer-md"></i></span><div id="themes"></div></a>
			<div class="clr"></div>
	 	</div>
		<div class="middle">
			<div class="middler">
				<div class="switcher-container">
					<!-- <div class="switcher-contain">
						<a class="teacher" tab="teacher" onclick="Portal.clickTab(this)"><?php echo $LANG['TEACHER']?></a>	
					</div>
					<div class="switcher-contain">
						<a class="student" tab="student" onclick="Portal.clickTab(this)"><?php echo $LANG['STUDENT']?></a>
					</div> -->
					<ul>
						<li class="switcher-item"><a class="teacher switcher" tab="teacher" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['TEACHER']?></a></li>
						<li class="switcher-item"><a class="student switcher" tab="student" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['STUDENT']?></a></li>
						<li class="switcher-item hide"><a class="other switcher" tab="other" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['OTHER']?></a></li>
						<li class="switcher-item hide"><a class="log switcher" tab="log" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['PORTAL_LOG']?></a></li>
						<li class="switcher-item hide"><a class="election switcher" tab="election" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['PORTAL_ELECTION']?></a></li>
						<li class="switcher-item hide"><a class="statistics switcher" tab="statistics" onclick="javascript:Portal.clickTab(this)"><?php echo $LANG['PORTAL_STATISTICS']?></a></li>
						<li class="switcher-item pull-right"><div class="switcher loading-container loader hide"><img src="/images/loading.1.gif" class="loading"/></div></li>
					</ul>
				</div>
				<div class="center log hide">
					<div class="text"><!-- <h2><?php echo $LANG['PORTAL_LOG']?></h2> --></div>
					<div class="container">
						<table id="log">
						</table>
					</div>
				</div>
				<div class="center stat hide portal-statistics">
					<div class="text"><h2><?php echo $LANG['PORTAL_STATISTICS_ONLINE']?></h2></div>
					<div class="clr"></div>
					<div class="container">
						<div id="stat-online">
						</div>
					</div>
					<div class="text">
						<h2><?php echo $LANG['PORTAL_STATISTICS_CLIENT_TOP']?></h2>
						<div class="date-container">
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-top-client select-date form-control" id="top-client-statDate" name="top-client-statDate">
									<option value="">日</option>
								</select>
							</div>
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-top-client select-month form-control" id="top-client-statMonth" name="top-client-statMonth">
									<option value="">月</option>
								</select>
							</div>
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-top-client select-year form-control" id="top-client-statYear" name="top-client-statYear">
									<option value="">年</option>
								</select>
							</div>
						</div>
					</div>
					<div class="clr"></div>
					<div class="container">
						<div id="stat-client-top">
						</div>
					</div>
					<div class="text">
						<h2><?php echo $LANG['PORTAL_STATISTICS_CLIENT_DATE']?></h2>
						<div class="date-container">
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-clientdate select-clientid form-control" id="clientdate-clientId" name="clientdate-clientId">
									<option value="">选择应用</option>
									<?php foreach ($clients as $c) {?>
									<option value="<?php echo $c['clientId']?>"><?php echo $c['clientName']?></option>
									<?php }?>
								</select>
							</div>
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-clientdate select-date form-control" id="clientdate-statDate" name="clientdate-statDate">
									<option value="">日</option>
								</select>
							</div>
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-clientdate select-month form-control" id="clientdate-statMonth" name="clientdate-statMonth">
									<option value="">月</option>
								</select>
							</div>
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-clientdate select-year form-control" id="clientdate-statYear" name="clientdate-statYear">
									<option value="">年</option>
								</select>
							</div>
						</div>
					</div>
					<div class="clr"></div>
					<div class="container">
						<div id="stat-clientDate">
						</div>
					</div>
					<div class="text">
						<h2><?php echo $LANG['PORTAL_STATISTICS_USER_TOP']?></h2>
						<div class="date-container">
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-top-user select-date form-control" id="top-user-statDate" name="top-user-statDate">
									<option value="">日</option>
								</select>
							</div>
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-top-user select-month form-control" id="top-user-statMonth" name="top-user-statMonth">
									<option value="">月</option>
								</select>
							</div>
							<div class="pull-right form-group mnt6 ml15">
								<select class="stat-top-user select-year form-control" id="top-user-statYear" name="top-user-statYear">
									<option value="">年</option>
								</select>
							</div>
						</div>
					</div>
					<div class="clr"></div>
					<div class="container">
						<div id="stat-user-top">
						</div>
					</div>
					
				</div>
				<div class="center">
					<div class="text"><h2><?php echo $LANG['PORTAL_SSO_SIGNIN']?></h2></div>
					<!-- <div class="loader loading-wrapper fade hide"></div> -->
					<!-- <div id="divPageLoading" class="loader loading-container hide"><img src="/images/loading.1.gif" class="loading"/><span></span></div> -->
					<div id="appsbysso" class="container appsbysso">
					</div>
					<div class="text"><h2><?php echo $LANG['PORTAL_TRANDITIONAL_SIGNIN']?></h2></div>
					<div id="appsbytran" class="container appsbytran">
					</div>
				</div>
			</div>
		</div>
		<div id="bottom">
			<div class="bottom_main">
				<ul class="clearfix">
					<li>
						<h4>学校概况</h4>
						<a href="http://www.lixin.edu.cn/default.php?mod=article&do=detail&tid=19" target="_blank">学校简介</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=article&do=detail&tid=128">办学思想</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=c&s=ssa9cf777">现任领导</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=article&do=detail&tid=89">学校标识</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=pic&do=detail&tid=1&lid=159&lid=159">校园景观</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=c&s=ssd9e55d8">多媒体展示</a>
					</li>
					<li>
						<h4><a href="http://www.lixin.edu.cn/default.php?mod=c&s=ss84215fc">管理机构</a></h4>
					</li>
					<li>
						<h4><a href="http://www.lixin.edu.cn/default.php?mod=c&s=ssfe7bf4f">教学院部</a></h4>
					</li>
					<li>
						<h4>人才培养</h4>
						<a href="http://jiaowuchu.lixin.edu.cn/index.aspx">本专科生</a>
						<a href="http://gs.lixin.edu.cn/">研究生</a>
						<a href="http://gjjl.lixin.edu.cn/">留学生</a>
						<a href="http://jxjy.lixin.edu.cn/">继续教育</a>
						<a href="">考研在线</a>
						<a href="http://maud.lixin.edu.cn/"> 审计硕士</a>
					</li>
					<li>
						<h4>学术研究</h4>
						<a href="http://maud.lixin.edu.cn/">学术活动信息</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=article&fid=19">研究机构</a>
						<a href="http://xueke.lixin.edu.cn/">学科建设处</a>
						<a href="http://kyc.lixin.edu.cn/">科研处</a>
						<a href="http://lxgz.cbpt.cnki.net/WKB/WebPublication/index.aspx">学报</a>
						<a href="http://camuseum.lixin.edu.cn/">会计博物馆</a>
					</li>
					<li>
						<h4>招生就业</h4>
						<a href="http://www.lixin.edu.cn/default.php?mod=c&s=ssbddec61">阳光招生</a>
						<a href="http://jiuye.lixin.edu.cn/index.action">就业指导</a>
					</li>
					<li>
						<h4>海外交流</h4>
						<a href="http://gjjlc.lixin.edu.cn/">国际交流与合作</a>
						<a href="http://gjjl.lixin.edu.cn/">国际交流学院</a>
						<a href="http://gjjlc.lixin.edu.cn/">访问生项目</a>
						<a href="http://gjjlc.lixin.edu.cn/">交换生项目</a>
						<a href="http://gjjlc.lixin.edu.cn/">短期访学项目</a>
					</li>
					<li>
						<h4>校园生活</h4>
						<a href="http://xschu.lixin.edu.cn/xueshengchu/default.asp">学生处</a>
						<a href="http://jiuye.lixin.edu.cn/index.action">就业指导中心</a>
						<a href="http://tw.lixin.edu.cn/">团委</a>
						<a href="">学联</a>
						<a href="http://xiaoyou.lixin.edu.cn/">校友会</a>
						<a href="http://jijinhui.lixin.edu.cn/">潘序伦教育发展基金会</a>
						<a href="http://wmzx.lixin.edu.cn/">文明在线</a>
					</li>
					<li>
						<h4>服务指南</h4>
						<a href="http://it.lixin.edu.cn/">数字校园</a>
						<a href="http://jiaowuchu.lixin.edu.cn/xiaoli1.htm">校历</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=c&s=ss9cf6a6d">校园黄页</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=c&s=ssf3ac969">部门邮箱</a>
						<a href="http://www.lixin.edu.cn/default.php?mod=c&s=ss9145059">校园风景</a>
					</li>
					<li>
						<h4><a href="http://www.lixin.edu.cn/default.php?mod=c&s=ss6b2a1b2">信息公开</a></h4>
					</li>
				</ul>
				
			</div>
			
		</div>
		<div class="copyright">
			<div class="copycontainer">
				<div class="copy"><span class="title"><?php echo $LANG['PORTAL_DCUX']?> <?php echo $LANG['PORTAL_DCUX_SUPPORT']?></span><span class="extra"></span></div>
				<div class="support"><span class="title">©Copyright <?php echo $LANG['PORTAL_START_YEAR']?>-<?php echo $LANG['CURRENT_YEAR']?> <?php echo $LANG['PORTAL_YOUR_NAME']?></span><span class="extra"></span></div>
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
</html>