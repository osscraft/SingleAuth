<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div class="modify">
	<form action="user.php" method="get">
		<input type="hidden" name="key" value="modify"/>
		<table border="1" class="table edit">
	      <tbody class="row1">
	        <tr class="row2">
				<th width="120"><?php echo $LANG['UID'];?></th>
				<td><input type="text" name="uid" value="<?php echo $USER['uid'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['USERNAME'];?></th>
				<td><input type="text" name="username" value="<?php echo $USER['username'];?>" maxLength="20"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['IS_ADMIN'];?></th>
				<td><input type="text" name="isAdmin" value="<?php echo $USER['isAdmin'];?>" maxLength="1"/></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:;" onclick="javascript:submitUser(document.getElementsByTagName('form')[0]);">确认修改</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
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