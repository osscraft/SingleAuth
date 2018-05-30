<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
	<form action="setting.php" method="get" class="modify">
		<input type="hidden" name="key" value="modify"/>
		<table border="1" class="table edit">
          <tbody class="row1">
			<tr class="row2">
				<th width="120"><?php echo $LANG['K'];?></th>
				<td><input type="text" id="k" name="k" value="<?php echo $SETTING['k'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['V'];?></th>
				<td><input type="text" id="v" name="v" value="<?php echo $SETTING['v'];?>"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['INFO'];?></th>
				<td><input type="text" id="info" name="info" value="<?php echo $SETTING['info'];?>"/></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:submitSetting(document.getElementsByTagName('form')[0]);">确认修改</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
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