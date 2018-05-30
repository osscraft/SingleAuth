<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $LANG['PORTAL_LOG_TITLE']?></title>
<!--<link href="css/loguser.css" rel="stylesheet" type="text/css" />-->
<link href="lib/flexigrid-1.1/css/flexigrid.css" rel="stylesheet" type="text/css" />
<link href="cache/css/log.css" type="text/css" rel="stylesheet" />
<!--<link href="cache/css/flex.css" type="text/css" rel="stylesheet" />-->
<!--<script type="text/javascript" src="lib/jquery/jquery.js"></script>
<script type="text/javascript" src="lib/jquery/jquery.cookie.js"></script>
<script type="text/javascript" src="lib/jquery/jquery.ba-bbq.js"></script>
<script type="text/javascript" src="lib/jquery/jquery.observehashchange.js"></script>-->
<script type="text/javascript" src="cache/js/jquery.js"></script>
<!--<script type="text/javascript" src="lib/flexigrid-1.1/js/flexigrid.js"></script>
<script type="text/javascript" src="js/loguser.index.js"></script>-->
<script type="text/javascript" src="cache/js/log.js"></script>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="header_block">
      <div class="logo"><a href="log.html"><img src="<?php echo $CFG['logo_portal_url']?>"  border="0" alt="logo" /></a></div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="clr"></div>
  <div class="top_bg2"></div>
  <div class="clr"></div>
  <div class="body">
    <div class="body_block">
      <div class="body_menu">
        <div class="body_menubar"><div class="body_menubarfont"><?php echo $LANG['PORTAL_LOG_REPORT']?></div>
        </div>
        <div class="body_menuall">
          <div style="font-size:12px;"><a href="log.html#!key=loguser"><?php echo $LANG['PORTAL_LOG_TITLE']?></a></div>
        </div>
      </div>
      <div class="body_right">
        <div id="flex">
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="clr"></div>
  <div class="footer">
    <div class="footer_block">
      <p class="leftt"><span><a href="http://www.dcux.com"><?php echo $LANG['PORTAL_DCUX']?></a>&nbsp;&nbsp;<?php echo $LANG['PORTAL_DCUX_SUPPORT']?></span>
      <p class="rightt"><span>©Copyright <?php echo $LANG['PORTAL_START_YEAR']?>-<?php echo $LANG['CURRENT_YEAR']?> <?php echo $LANG['PORTAL_YOUR_NAME']?></span></p>
      <div class="clr"></div>
    </div>
  </div>
</div>
</body>
<html>
