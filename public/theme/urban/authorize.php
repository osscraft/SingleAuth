<!doctype html>
<html lang="zh-cn" ng-app>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo empty($title)?'':$title;?></title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="/cache/css/admin.urban.css" />
    <style type="text/css">
	body{
		background:#fff;
	}
    .case_cover_img {
        width: 90px;
        height: 90px;
        border-radius: 5px;
        /*box-shadow: 0 2px 2px rgba(0,0,0,.2);*/
    }
    .case_name {
        display: inline-block;
        width: 110px;
        margin: 0 -15px;
        overflow: hidden;
        color: #333;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .notice-info {
        color:#FF0000;
    }
    .form-group {
        padding: 0 20px;
    }
    .title {
        display: block;
        position: absolute;
        left: 0px;
        right: 0;
        bottom: 0;
        color: #333;
        font-size: 12px;
        font-weight: bold;
        line-height: 22px;
    }
	.mask { 
		display: block;
		position: absolute;
		top: -4px;
		left: 0px;
		right:0;
		height: 114px;
		background-image: url(/images/icons_20121001/appsIconbg.png);
		background-position:center center;
		background-repeat:no-repeat; 
		z-index:-1;
	}
	.mb5{
		position:relative;
	}
	.bg-white{
		background:none;
	}
    .qrlogin-container {
        position:absolute;
        right:25px;
        top:25px;
        width:196px;
        text-align:center;
    }
    .qrrefresh {
        display: none;
    }
	.qrlogin:hover, .qrrefresh:hover {
		color:red;
	}
	.no-padding{
		padding:0!important;
	}
    </style>
    <?php if(empty($isMobile)) {?>
    <script type="text/javascript">
    // Let the library know where WebSocketMain.swf is:
    WEB_SOCKET_SWF_LOCATION = "/lib/web-socket-js/WebSocketMain.swf";
    //WEB_SOCKET_FORCE_FLASH = true;
    WEB_SOCKET_DEBUG = true;
    </script>
    <script type="text/javascript" src="/lib/web-socket-js/swfobject.js"></script>
    <script type="text/javascript" src="/lib/web-socket-js/web_socket.js"></script>
    <?php }?>
    <script type="text/javascript" src="cache/js/authorizelib.js"></script>
    <script type="text/javascript" src="cache/js/authorize.js"></script>
</head>
<body>
    <div class="app layout-fixed-header bg-white usersession">
        <div class="full-height">
            <div class="center-wrapper">
                <div class="center-content">
                    <?php if(empty($user) && empty($isMobile)) {?>
                    <div class="qrlogin-container"><a id="qrlogin" class="qrlogin">二维码登录</a> <a id="qrrefresh" class="qrrefresh">更新</a><div id="qrcode" style="display:none"><img id="code" style="display:none" src="/images/loading.1.gif"/><div></div><span id='scaned' style="display:none;">已扫描，请在手机端确认登录</span></div></div>
                    <?php }?>
                    <div class="row no-margin">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                            <form id="login-form" role="form" action="authorize.php?client_id=<?php echo $client['clientId'];?>&response_type=<?php echo $response_type;?>&redirect_uri=<?php echo urlencode($client['redirectURI']);?><?php echo empty($state)?'':('&state='.urlencode($state));?>" method="post" class="form-layout">
                                <?php if(!empty($referer)) {?>
                                <input id="referer" name="referer" type="hidden" value="<?php echo $referer;?>"/>
                                <?php }?>
                                <div class="text-center mb10"> <img src="<?php echo $CFG['logo_main_url'];?>"> </div>
                                <p class="text-center mb10"><?php echo $LANG['PORTAL_WELCOME'];?></p>
                                <div class="text-center mb15">
                                    <p class="mb5">
                                        <img class="case_cover_img" src="<?php echo empty($client['clientLogoUri'])?"/images/ICON_BackGround.png":$client['clientLogoUri'];?>">
                                        <?php if(!empty($CFG['portal_app_has_title'])) {?><span class="title"><?php echo $client['clientName'];?></span><?php }?>
										<span class="mask"></span>
                                    </p>
                                </div>
                                <div class="form-inputs form-group">
                                <?php if(!empty($user)) {?>
                                    <?php if(/*$redirect && */$delay) {?>
                                    <div class="mb5 skip-delay place-holder">
                                        <span id="skip_delay_seconds"><?php echo $seconds?></span>
                                        <span>秒后跳转，如果没跳转请点击</span>
                                        <!--<a href="<?php echo $redirect?>">--><a href="javascript:Authorize.login();">这里</a>
										<!--<input type="hidden" class="hide" id="client_id" value="<?php echo $client_id;?>"/>-->
										<input type="hidden" class="hide" id="client_type" value="<?php echo $client_type;?>"/>
										<input type="hidden" class="hide" id="skip_delay" value="<?php echo $delay;?>"/>
										<!--<input type="hidden" class="hide" id="skip_url" value="<?php echo $redirect;?>"/>-->
                                    </div>
                                    <input id="username" name="username" type="text" value="<?php echo $user['username'];?>" class="form-control input-lg" placeholder="用户名" disabled />
                                    <?php } else {?>
                                    <div class="mb5 place-holder">
                                        <span id="user_info" class="notice-info"><?php echo empty($error)?'&nbsp;':$error;?></span>
                                    </div>
                                    <input id="username" name="username" type="text" value="<?php echo $user['username'];?>" class="form-control input-lg" placeholder="用户名" disabled />
                                    <button class="btn btn-success btn-block btn-lg mb15" type="submit" onclick="return validateUsernameAndPassword();"> <span><?php echo $LANG['PORTAL_GRANT'];?></span> </button>
                                    <?php }?>
                                    <!-- other -->
                                    <input type="hidden" name="loggedin" value="1" />
                                    <input type="hidden" name="clientname" value="<?php echo $client['clientName'];?>" />
                                    <input type="hidden" name="otherlogin" id="otherlogin"/>
                                    <!-- <div class="text-center mb15">
                                        <label class="oauth_input_label">帐号:</label><?php echo $user['username'];?>
                                    </div> -->
                                    <p> <!-- <a href="extras-signup.html">注册</a> ·  -->
                                        <!--<a href="extras-signup.html"><?php echo $LANG['OTHER_ACCOUNT'];?>?</a>-->
										<?php if($CFG['OTHER_ACCOUNT']) {?>
										<a id="other_account"><?php echo $LANG['OTHER_ACCOUNT'];?>?</a>
										<?php }?>
                                    </p>
                                <?php } else {?>
                                    <div class="mb5">
                                        <span id="user_info" class="notice-info"><?php echo empty($error)?'&nbsp;':$error;?></span>
                                    </div>
                                    <input id="username" name="username" type="text" class="form-control input-lg" placeholder="<?php echo $LANG['USERNAME'];?>" />
                                    <input id="password" name="password" type="password" class="form-control input-lg" placeholder="<?php echo $LANG['PASSWORD'];?>" />
                                    <?php if(!empty($is_verify)) {?>
                                    <div class="col-sm-8 no-padding"><input type="text" id="code_char" name="verifyCode"  class="form-control input-lg" maxlength="4" placeholder="<?php echo $LANG['VERIFY_CODE'];?>"/></div>
                                    <div class="col-sm-3 no-padding pull-right">
                                    <input type="image" id="getcode_char" class="form-control no-padding no-border input-lg" alt="<?php echo $LANG['VERIFY_CODE_IMAGE'];?>" title="<?php echo $LANG['VERIFY_CODE_IMAGE'];?>"/>
                                    <input type="hidden" id="verifyCodeSuccess" name="verifyCodeSuccess" value=""/>
                                    </div>
                                    <!-- <label id="code_info" style="float:left;display:block;line-height:25px;"></label> -->
                                    <?php }?>
                                    <button class="btn btn-success btn-block btn-lg mb15" type="submit" onclick="return validateUsernameAndPassword()";> <span><?php echo $LANG['PORTAL_SIGNIN_AND_GRANT'];?></span> </button>
                                    <p> <!-- <a href="extras-signup.html">注册</a> ·  -->
                                        <!--<a href="<?php echo $CFG['forgot_password_url'];?>"><?php echo $LANG['FORGOT_PASSWORD'];?>?</a>-->
										<a id="forgot_password" data-href="<?php echo $CFG['forgot_password_url'];?>"><?php echo $LANG['FORGOT_PASSWORD'];?>?</a>
                                        <!-- <a id="login_by_yb" data-href=""><?php echo $LANG['LOGIN_BY_YB'];?></a> -->
                                    </p>
                                <?php }?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
