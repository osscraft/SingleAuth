<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
	<form action="client.php" method="get" class="create">
		<input type="hidden" name="key" value="create"/>
		<table border="1" class="table create">
          <tbody class="row1">
		    <tr class="row2">
				<th width="120"><?php echo $LANG['CLIENT_ID'];?></th>
				<td><input type="text" id="clientId" name="clientId" value="" maxLength="32"/><label>32字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_SECRET'];?></th>
				<td><span id="clientSecretText" name="clientSecretText"></span><input id="clientSecret" type="hidden" name="clientSecret" value="<?php echo $CLIENT['clientSecret'];?>" readonly="readonly"/><input type="button" value="随机生成" onclick="javascript:$.geneClientSecret();"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_NAME'];?></th>
				<td><input type="text" id="clientName" name="clientName" value="" maxLength="50" /><label>50字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_TYPE'];?></th>
				<td><select id="clientType" name="clientType" size="1"　readonly="readonly"><option value="webApp" selected="true">WEB应用</option><option value="jsApp" >JS应用</option><option value="desktopApp" >桌面应用</option></select></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_DESCRIBE'];?></th>
				<td><textarea id="clientDescribe" name="clientDescribe" maxLength="1000"></textarea><!--<input type="text" name="clientDescribe" value="" maxLength="1000" />--><label>1000字符以内</label></td>
			</tr>
			<tr class="row2">
				<th ><?php echo $LANG['REDIRECT_URI'];?></th>
				<td><input type="text" id="redirectURI" name="redirectURI" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_SCOPE'];?></th>
				<td><input type="text" id="clientScope" name="clientScope" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_LOCATION'];?></th>
				<td><input type="text" id="clientLocation" name="clientLocation" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_LOGOURI'];?></th>
				<td><input type="text" id="clientLogoUri" name="clientLogoUri" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_ISSHOW'];?></th>
				<td><input type="text" id="clientIsShow" name="clientIsShow" value="0"/><label title="0为不在首页显示，大于0的可进行从大到小排序">0-255的数值</label></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['CLIENT_VISIBLE'];?></th>
				<td>
					<select id="clientVisible" name="clientVisible" size="1"　readonly="readonly">
						<option value="0" selected="true">全部</option>
						<option value="1" >教师</option>
						<option value="2" >学生</option>
						<option value="3" >其他</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter">
					<a href="javascript:void(0);" onclick="javascript:$.submitClient();">创建</a>
					<a href="javascript:history.go(-1);">返回</a>
				</td>
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