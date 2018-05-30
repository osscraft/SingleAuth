<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
	<form action="client.php" method="get" class="modify">
		<input type="hidden" name="key" value="modify"/>
		<table border="1" class="table edit">
          <tbody class="row1">
			<tr class="row2">
				<th width="120"><?php echo $LANG['ID'];?></th>
				<td><?php echo $CLIENT['id'];?><input type="hidden" id="id" name="id" value="<?php echo $CLIENT['id'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_ID'];?></th>
				<td><?php echo $CLIENT['clientId'];?><input type="hidden" id="clientId" name="clientId" value="<?php echo $CLIENT['clientId'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_SECRET'];?></th>
				<td><span id="clientSecretText" name="clientSecretText"><?php echo $CLIENT['clientSecret'];?></span><input id="clientSecret" type="hidden" name="clientSecret" value="<?php echo $CLIENT['clientSecret'];?>" readonly="readonly"/><input type="button" value="随机生成" onclick="javascript:$.geneClientSecret();"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_NAME'];?></th>
				<td><input type="text" id="clientName" name="clientName" value="<?php echo $CLIENT['clientName'];?>" maxLength="50"/><label>50字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_TYPE'];?></th>
				<td><select id="clientType" name="clientType" size="1"　readonly="readonly">
						<!-- IF CLIENT.clientType == "webApp" -->
						<?php if($CLIENT['clientType'] == "webApp") {?>
						<option value="webApp" selected="true">
						<!-- ELSE -->
						<?php } else {?>
						<option value="webApp" >
						<!-- ENDIF --><?php }?>WEB应用</option>
						<!-- IF CLIENT.clientType == "jsApp" -->
						<?php if($CLIENT['clientType'] == "jsApp") {?>
						<option value="jsApp" selected="true" >
						<!-- ELSE -->
						<?php } else {?>
						<option value="jsApp" >
						<!-- ENDIF --><?php }?>JS应用</option>
						<!-- IF CLIENT.clientType == "desktopApp" -->
						<?php if($CLIENT['clientType'] == "desktopApp") {?>
						<option value="desktopApp" selected="true" >
						<!-- ELSE -->
						<?php } else {?>
						<option value="desktopApp" >
						<!-- ENDIF --><?php }?>桌面应用</option>
					</select>
				</td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_DESCRIBE'];?></th>
				<td><textarea id="clientDescribe" name="clientDescribe"><?php echo $CLIENT['clientDescribe'];?></textarea><!--<input type="text" name="clientDescribe" value="<?php echo $CLIENT['clientDescribe'];?>" />--><label>1000字符以内</label></td>
			</tr>
			<tr class="row2">
				<th ><?php echo $LANG['REDIRECT_URI'];?></th>
				<td><input type="text" id="redirectURI" name="redirectURI" value="<?php echo $CLIENT['redirectURI'];?>" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_SCOPE'];?></th>
				<td><input type="text" id="clientScope" name="clientScope" value="<?php echo $CLIENT['clientScope'];?>" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_LOCATION'];?></th>
				<td><input type="text" id="clientLocation" name="clientLocation" value="<?php echo $CLIENT['clientLocation'];?>" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_LOGOURI'];?></th>
				<td><input type="text" id="clientLogoUri" name="clientLogoUri" value="<?php echo $CLIENT['clientLogoUri'];?>" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_ISSHOW'];?></th>
				<td><input type="text" id="clientIsShow" name="clientIsShow" value="<?php echo $CLIENT['clientIsShow'];?>" maxLength="3"/><label title="0为不在首页显示，大于0的可进行从大到小排序">0-255的数值</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_VISIBLE'];?></th>
				<td>
					<select id="clientVisible" name="clientVisible" size="1">
						<option value="0" <?php if($CLIENT['clientVisible'] == 0) echo 'selected="true"';?> >全部</option>
						<option value="1" <?php if($CLIENT['clientVisible'] == 1) echo 'selected="true"';?> >教师</option>
						<option value="2" <?php if($CLIENT['clientVisible'] == 2) echo 'selected="true"';?> >学生</option>
						<option value="3" <?php if($CLIENT['clientVisible'] == 3) echo 'selected="true"';?> >其他</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:void(0);" onclick="javascript:$.submitClient();">确认修改</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
			</tr>
          </tbody>
		</table>
	</form>
</div>
<p></p>

   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>