<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div class="modify">
	<form action="ldapConfig.php" method="get">
		<input type="hidden" name="key" value="modify"/>
		<table border="1" class="table edit">
	      <tbody class="row1">
	        <tr class="row2">
				<th width="120"><?php echo $LANG['HOST'];?></th>
				<td><input type="text" name="host" value="<?php echo $LDAPCONFIG['host'];?>" maxLength="100" /></td>
			</tr>
	        <tr class="row2">
				<th><?php echo $LANG['BASE_DN'];?></th>
				<td><input type="text" name="baseDN" value="<?php echo $LDAPCONFIG['baseDN'];?>" maxLength="255" /></td>
			</tr>
	        <tr class="row2">
				<th><?php echo $LANG['ROOT_DN'];?></th>
				<td><input type="text" name="rootDN" value="<?php echo $LDAPCONFIG['rootDN'];?>" maxLength="255" /></td>
			</tr>
	        <tr class="row2">
				<th><?php echo $LANG['ROOT_PW'];?></th>
				<td><input type="text" name="rootPW" value="<?php echo $LDAPCONFIG['rootPW'];?>" maxLength="100" /></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:;" onclick="javascript:submitLDAPConfig(document.getElementsByTagName('form')[0]);">确认修改</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
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