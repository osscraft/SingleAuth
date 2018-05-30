<?php include __DIR__ . '/header.php';?>
<form method="post" action="authorize.php?client_id=<?php echo $client['clientId'];?>&response_type=<?php echo $response_type;?>&redirect_uri=<?php echo urlencode($client['redirectURI']);?><?php echo empty($state)?'':('&state='.urlencode($state));?>">
        <?php if(!empty($referer)) {?>
        <input id="referer" name="referer" type="hidden" value="<?php echo $referer;?>"/>
        <?php }?>
        <div style="margin: 0 auto;width: 650px;">
                <div style="background: url(images/signinbox_header_bg.png); height: 37px; width: 650px;"></div>
                <div style="background: url(images/signinbox_body_bg.png); height: 296px; width: 650px;">
                    <div id="center_left" style="float:left;width:450px;height: 296px;">
                        <!-- IF IS_LOGIN -->
                        <?php if(!empty($user)) {?>
                        <div align="left" style="padding:0 0 0 30px;margin:30px 0 0 0px;font-size:14px;"><?php echo $LANG['PORTAL_HAD_ACCOUNT']?></div>
                        <div align="left" style="padding:0 0 0 30px;font-size:14px;"><?php echo $LANG['PORTAL_CLICK_TO_SIGNIN']?>"<?php echo $client['clientName'];?>"</div>
                        <!-- ELSE -->
                        <?php } else {?>
                        <div align="left" style="padding:0 0 0 30px;margin:30px 0 0 0px;font-size:14px;"><?php echo $LANG['PORTAL_FILL_IN_YOUR_INFO']?></div>
                        <div align="left" style="padding:0 0 0 30px;font-size:14px;"><?php echo $LANG['PORTAL_USE_APP']?>"<?php echo $client['clientName'];?>"</div>
                        <!-- ENDIF -->
                        <?php }?>

                        <div align="left" style="padding:0 0 0 90px;margin:10px 0px;font-size:14px;">
                                <span id="user_info" style="color:#FF0000" >&nbsp;<?php echo empty($error)?'':$error;?></span>
                        </div>

                        <!--<div align="left" style="padding:0 0 0 30px;margin:10px 0 0 0px;">-->
                                <!-- IF IS_LOGIN -->
                                <?php if(!empty($user)) {?>
                                <div style="font-size:14px;padding:0 0 0 30px;height:30px;">
                                        <label class="oauth_input_label"><?php echo $LANG['ACCOUNT']?>:</label><?php echo $user['username'];?>
                                </div>
                                <div style="width:100%;height:10px;"></div>
                                <div style="font-size:14px;padding:0 0 0 30px;">
                                        <input type="hidden" name="loggedin" value="1" />
                                        <input type="hidden" name="clientname" value="<?php echo $client['clientName'];?>" />
										<input type="hidden" name="otherlogin" id="otherlogin"/>
                                        <input type="submit" name="accept" value="<?php echo $LANG['PORTAL_GRANT']?>" style="height: 30px;width: 80px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php if($CFG['OTHER_ACCOUNT']) {?>
										<input type="submit" name="accept" id="other_account" value="其他帐号" style="height: 30px;width: 80px;" />
										<?php }?>
                                </div>
                                <!-- ELSE -->
                                <?php } else {?>
                                <div style="font-size:14px;padding:0 0 0 30px;height:30px;">
                                        <label class="oauth_input_label" style="float:left;display:block;width:60px;line-height:25px;"><?php echo $LANG['ACCOUNT']?>&nbsp;&nbsp;</label>
                                        <input type="text" id="username" name="username" value="" style="line-height:25px;float:left;display:block;height: 23px; width: 200px;" onblur="checkUsername();"/>
                                        <div class="clr"></div>
                                </div>
                                        <!-- IF SHOW_VERIFY_CODE -->
                                        <?php if(!empty($is_verify)) {?>
                                <div style="width:100%;height:10px;"></div>
                                        <!-- ELSE -->
                                        <?php } else {?>
                                <div style="width:100%;height:30px;"></div>
                                        <!-- ENDIF -->
                                        <?php }?>
                                <div style="font-size:14px;padding:0 0 0 30px;height:30px;">
                                        <label class="oauth_input_label" style="float:left;display:block;width:60px;line-height:25px;"><?php echo $LANG['PASSWORD']?>&nbsp;&nbsp;</label>
                                        <input type="password" id="password" name="password" value="" style="line-height:25px;float:left;display:block;height: 23px; width: 200px;" onblur="checkPassword();"/>
                                        <div class="clr"></div>
                                </div>

                                        <!-- IF SHOW_VERIFY_CODE -->
                                        <?php if(!empty($is_verify)) {?>
                                <div style="width:100%;height:10px;"></div>
                                <div style="font-size:14px;padding:0 0 0 30px;height:30px;">
                                        <label class="oauth_input_label" style="line-height:25px;float:left;display:block;width:60px;"><?php echo $LANG['VERIFY_CODE']?></label>
                                        <input type="text" id="code_char"  name="verifyCode"  maxlength="4" style="line-height:25px;width:90px;height: 23px;float:left;display:block;"/>
                                        <input type="image" id="getcode_char" alt="<?php echo $LANG['VERIFY_CODE_IMAGE']?>" title="<?php echo $LANG['VERIFY_CODE_IMAGE']?>" style="float:left;display:block;margin:0px 10px 0px 10px;width:100px;height: 25px;"/>
                                        <input type="hidden" id="verifyCodeSuccess" name="verifyCodeSuccess" value=""/>
                                        <label id="code_info" style="float:left;display:block;line-height:25px;"></label>
                                        <div class="clr"></div>
                                </div>
                                <div style="width:100%;height:10px;"></div>
                                        <!-- ELSE -->
                                        <?php } else {?>
                                <div style="width:100%;height:30px;"></div>
                                        <!-- ENDIF -->
                                        <?php }?>
                                <div style="margin:0px;font-size:14px;padding:0 0 0 30px;height:30px;">
                                        <input type="hidden" name="errorCount" value="{ERROR_COUNT}"/>
                                        <input type="hidden" name="clientname" value="<?php echo $client['clientName'];?>" />
                                        
                                        <input type="submit" name="accept" value="<?php echo $LANG['PORTAL_SIGNIN_AND_GRANT']?>" onclick=" return validateUsernameAndPassword()" style="height: 30px; width: 80px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="forgot_password" type="button" name="forgot" value="<?php echo $LANG['FORGOT_PASSWORD']?>" style="height: 30px; width: 80px;margin:0px 0px 0px 20px;" data-href="<?php echo $CFG['forgot_password_url']?>"/>
                                </div>


                                <!-- ENDIF -->
                                <?php }?>
                        <!--</div>-->
                    </div>
                    <div id="center_right" style="float:left;width:200px;height: 296px;">
                        <img style="margin: 105px 0 0 0;width:90px;height:90px;" src="<?php echo $client['clientLogoUri'];?>" alt="客户端uri" />
                        <div id="mask" style="margin: -95px 0 0 -12px;background: url(images/icons_20121001/appsIconbg.png); height: 114px; width: 116px;"></div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div style="background: url(images/signinbox_footer_bg.png); height: 73px; width: 650px;">
                </div>
        </div>
</form>
<?php include __DIR__ . '/footer.php';?>