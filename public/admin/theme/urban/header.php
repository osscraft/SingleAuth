<!doctype html>
<html lang="zh-cn" ng-app>
<head>
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $LANG['ADMIN_TITLE'];?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="/lib/datatables/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/lib/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/lib/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/lib/animate.css">
    <link rel="stylesheet" type="text/css" href="/lib/climacons-font/climacons-font.css">
    <link rel="stylesheet" type="text/css" href="/lib/roboto/roboto.css">
    <link rel="stylesheet" type="text/css" href="/lib/panel/panel.css">
    <link rel="stylesheet" type="text/css" href="/lib/icons-feather/feather.css">
    <!-- <link rel="stylesheet" type="style/css" href="/lib/glyphicons/glyphicons.css"> -->
    <link rel="stylesheet" type="text/css" href="/lib/summernote/dist/summernote.css">
    <link rel="stylesheet" type="text/css" href="/lib/sweetalert/dist/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="/lib/flexigrid-1.1/css/flexigrid.css"/>
    <link rel="stylesheet" type="text/css" href="/lib/checkBo/checkBo.css"/>
    <link rel="stylesheet" type="text/css" href="/lib/perfect-scrollbar/css/perfect-scrollbar.css"/>
    <link rel="stylesheet" type="text/css" href="/cache/css/admin.urban.css">
    <!-- <link rel="stylesheet" type="style/css" href="/lib/rickshaw/rickshaw.css"> -->
    <!-- <script src="/lib/angular/angular.js"></script> -->
    <script src="/lib/underscore.js"></script>
    <script src="/cache/js/adminlib.js"></script>
    <script src="/cache/js/admin.js"></script>
    <script src="/cache/js/admin.urban.js"></script>
    <style type="text/css">
      .toggle-chat {
        display: none;
      }
    </style>
</head>
<body>
<div class="quick-launch-panel">
  <div class="container">
    <div class="quick-launcher-inner"> <a href="javascript:;" class="close" data-toggle="quick-launch-panel">×</a>
      <div class="css-table-xs">
        <div class="col"> <a href="app-calendar.html"> <i class="icon-marquee"></i> <span>Calendar</span> </a> </div>
        <div class="col"> <a href="app-gallery.html"> <i class="icon-drop"></i> <span>Gallery</span> </a> </div>
        <div class="col"> <a href="app-messages.html"> <i class="icon-mail"></i> <span>Messages</span> </a> </div>
        <div class="col"> <a href="app-social.html"> <i class="icon-speech-bubble"></i> <span>Social</span> </a> </div>
        <div class="col"> <a href="charts-flot.html"> <i class="icon-pie-graph"></i> <span>Analytics</span> </a> </div>
        <div class="col"> <a href="javascript:;"> <i class="icon-esc"></i> <span>Documentation</span> </a> </div>
      </div>
    </div>
  </div>
</div>
<div class="app layout-fixed-header<?php echo empty($user) ? ' layout-smallest-menu' : '';?>">
  <?php if(!empty($user)) {?>
  <div class="sidebar-panel offscreen-left">
    <div class="brand">
      <div class="brand-logo"> <img src="<?php echo $CFG['logo_admin_url'];?>" height="35" alt=""> </div>
      <div class="profile pull-right align-middle">
        <span><?php echo $user['username'];?></span>
        <!-- <img src="/images/user_male.png" class="header-avatar img-circle ml10" alt="<?php echo $user['username'];?>" title="<?php echo $user['username'];?>"> -->
        <a class="user-signout ml10" href="/admin/logout.php" title="<?php echo $LANG['LOGOUT'];?>" alt="<?php echo $LANG['LOGOUT'];?>"><i class="fa fa-sign-out fa-2"></i></a>
      </div>
      <a href="javascript:;" class="toggle-sidebar hidden-xs hamburger-icon v3" data-toggle="layout-small-menu"> <span></span> <span></span> <span></span> <span></span> </a>
  	</div>
    <nav role="navigation" class="ps-container">
      <ul class="nav">
      	<?php foreach ($menu as $m) {?>
      	<li class="<?php echo empty($m['active']) && empty($m['children'])?'':(empty($m['children'])?'active':(empty($m['active'])?'menu-accordion':'menu-accordion open'));?>">
      		<a href="<?php echo empty($m['children'])?$m['href']:'javascript:;';?>">
      			<?php if(!empty($m['icon'])) {?><i class="<?php echo $m['icon'];?>"> </i><?php }?>
      			<span><?php echo $m['name'];?></span>
          </a>
          	<?php if(!empty($m['children'])) {?>
          	<ul class="sub-menu">
          		<?php foreach ($m['children'] as $_m) {?>
          		<li class="<?php echo empty($_m['active']) && empty($_m['children'])?'':(empty($_m['children'])?'active':(empty($_m['active'])?'menu-accordion':'menu-accordion open'));?>">
          			<a href="<?php echo empty($_m['children'])?$_m['href']:'javascript:;';?>">
  		      			<?php if(!empty($_m['icon'])) {?><i class="<?php echo $_m['icon'];?>"> </i><?php }?>
  		      			<span><?php echo $_m['name'];?></span>
                </a>
		          	<?php if(!empty($_m['children'])) {?>
		          	<ul class="sub-menu">
		          		<?php foreach ($_m['children'] as $__m) {?>
		          		<li class="<?php echo empty($__m['active']) && empty($__m['children'])?'':(empty($__m['children'])?'active':(empty($__m['active'])?'menu-accordion':'menu-accordion open'));?>">
		          			<a href="<?php echo empty($__m['children'])?$__m['href']:'javascript:;';?>">
  				      			<?php if(!empty($__m['icon'])) {?><i class="<?php echo $__m['icon'];?>"> </i><?php }?>
  				      			<span><?php echo $__m['name'];?></span>
    				  			</a>
		          		</li>
		          		<?php }?>
		          	</ul>
		          	<?php }?>
          		</li>
          		<?php }?>
          	</ul>
          	<?php }?>
      	</li>
      	<?php }?>
      </ul>
    <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 0px;"><div class="ps-scrollbar-y" style="top: 0px; height: 0px;"></div></div></nav>
  </div>
  <?php } else {?>
  <?php }?>
  <div class="main-panel">
    <header class="header navbar">
      <div class="brand visible-xs">
        <div class="toggle-offscreen"> <a href="#" class=" hamburger-icon visible-xs" data-toggle="offscreen move-left" data-move="ltr"> <span></span> <span></span> <span></span> </a> </div>
        <?php if(empty($user)) {?>
        <div class="user-signin"> <a class="hamburger-icon visible-xs fa-hover" href="<?php echo $URL;?>"><i class="fa fa-sign-in fa-2"></i></a> </div>
        <?php }?>
        <div class="brand-logo"> <img src="<?php echo $CFG['logo_admin_url'];?>" height="35" alt=""> </div>
        <div class="toggle-chat"> <a href="javascript:;" class="toggle-chatbar hamburger-icon v2 visible-xs" data-toggle="layout-chat-open"> <span></span> <span></span> <span></span> </a> </div>
      </div>
      <?php if(!empty($user)) {?>
      <ul class="nav navbar-nav hidden-xs">
        <li>
          <ol class="breadcrumb navbar-text">
            <?php if(count($chain) > 1) {?>
            <li> <a href="/admin/"><i class="fa fa-home mr5"></i><?php echo $LANG['MAINPAGE'];?></a> </li>
            <?php }?>
            <?php foreach ($chain as $k => $m) {?>
              <?php if($k == count($chain) - 1) {?>
            <li class="active ng-binding">
                <?php if(!empty($m['icon'])) {?>
                <i class="<?php echo $m['icon'];?> mr5"> </i>
                <?php }?>
                <span><?php echo $m['name'];?></span>
            </li>
              <?php } else {?>
            <li>
              <a href="<?php echo $m['href'];?>">
                <?php if(!empty($m['icon'])) {?>
                <i class="<?php echo $m['icon'];?> mr5"> </i>
                <?php }?>
                <span><?php echo $m['name'];?></span>
              </a>
            </li>
              <?php }?>
            <?php }?>
          </ol>
        </li>
      </ul>
      <?php } else {?>
      <ul class="nav navbar-nav hidden-xs">
        <li>
          <ol class="breadcrumb navbar-logo mb10 mt10 pt0 pb0">
            <li>
              <div class="brand-logo"> <img src="<?php echo $CFG['logo_admin_url'];?>" height="35" alt=""> </div>
            </li>
          </ol>
        </li>
      </ul>
      <?php }?>
      <ul class="nav navbar-nav navbar-right hidden-xs">
        <li>
          <?php if(!empty($user)) {?>
          <a href="javascript:;" class="toggle-dropdown profile" data-toggle="dropdown">
            <span><?php echo $user['username'];?></span>
            <img src="/images/user_male.png" class="header-avatar img-circle ml10" alt="<?php echo $user['username'];?>" title="<?php echo $user['username'];?>">
          </a>
          <ul class="dropdown-menu">
            <li> <a href="/admin/logout.php"><i class="fa fa-sign-out fa-2"></i> <?php echo $LANG['LOGOUT'];?></a> </li>
          </ul>
          <?php } else {?>
          <a class="fa-hover" href="<?php echo $URL;?>"><i class="fa fa-sign-in fa-2"></i></a>
          <?php }?>
        </li>
        <li style="display:none;"> <a href="javascript:;" class="toggle-chatbar hamburger-icon v2" data-toggle="layout-chat-open"> <span></span> <span></span> <span></span> </a> </li>
      </ul>
    </header>