<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
	<form action="client.php" method="get" class="delete">
		<input type="hidden" name="key" value="delete"/>
		<table border="1" class="table remove">
          <tbody class="row1">
		    <tr class="row2">
				<th width="120"><?php echo $LANG['ID'];?></th>
				<td><input type="text" name="id" name="id" value="<?php echo $CLIENT['id'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_ID'];?></th>
				<td><input type="text" name="clientId" value="<?php echo $CLIENT['clientId'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_SECRET'];?></th>
				<td><input type="text" name="clientSecret" value="<?php echo $CLIENT['clientSecret'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_NAME'];?></th>
				<td><input type="text" name="clientName" value="<?php echo $CLIENT['clientName'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_TYPE'];?></th>
				<td><!-- IF CLIENT.clientType == "webApp" -->
					<?php if($CLIENT['clientType'] == "webApp") {?>
				    <input type="text" name="clientType" value="WEB应用" readonly="readonly" />
				    <!-- ELSEIF CLIENT.clientType == "jsApp" -->
					<?php } else if($CLIENT['clientType'] == "jsApp") {?>
				    <input type="text" name="clientType" value="JS应用" readonly="readonly" />
				    <!-- ELSEIF CLIENT.clientType == "desktopApp" -->
					<?php } else if($CLIENT['clientType'] == "desktopApp") {?>
				    <input type="text" name="clientType" value="桌面应用" readonly="readonly" />
				    <!-- ENDIF -->
					<?php }?>
				</td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_DESCRIBE'];?></th>
				<td><textarea name="clientDescribe" readonly="readonly"><?php echo $CLIENT['clientDescribe'];?></textarea><!--<input type="text" name="clientDescribe" value="<?php echo $CLIENT['clientDescribe'];?>" readonly="readonly"/>--></td>
			</tr>
			<tr class="row2">
				<th ><?php echo $LANG['REDIRECT_URI'];?></th>
				<td><input type="text" name="redirectURI" value="<?php echo $CLIENT['redirectURI'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_SCOPE'];?></th>
				<td><input type="text" name="clientScope" value="<?php echo $CLIENT['clientScope'];?>" readonly="readonly"/></td>
			</tr>
			<tr>
				<th><?php echo $LANG['CLIENT_LOCATION'];?></th>
				<td><input type="text" name="clientLocation" value="<?php echo $CLIENT['clientLocation'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_LOGOURI'];?></th>
				<td><input type="text" name="clientLogoUri" value="<?php echo $CLIENT['clientLogoUri'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_ISSHOW'];?></th>
				<td><input type="text" name="clientIsShow" value="<?php echo $CLIENT['clientIsShow'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_VISIBLE'];?></th>
				<td>
					<!-- IF CLIENT.clientVisible == 0 -->
					<?php if($CLIENT['clientVisible'] == 0) {?>
				    <input type="text" name="clientVisible" value="全部" readonly="readonly" />
				    <!-- ELSEIF CLIENT.clientVisible == 1 -->
					<?php } else if($CLIENT['clientVisible'] == 1) {?>
				    <input type="text" name="clientVisible" value="教师" readonly="readonly" />
				    <!-- ELSEIF CLIENT.clientVisible == 2 -->
					<?php } else if($CLIENT['clientVisible'] == 2) {?>
				    <input type="text" name="clientVisible" value="学生" readonly="readonly" />
				    <!-- ELSEIF CLIENT.clientVisible == 3 -->
					<?php } else if($CLIENT['clientVisible'] == 3) {?>
				    <input type="text" name="clientVisible" value="其他" readonly="readonly" />
				    <!-- ENDIF -->
					<?php }?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:void(0);" onclick="javascript:$('form').submit();">确认删除</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
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