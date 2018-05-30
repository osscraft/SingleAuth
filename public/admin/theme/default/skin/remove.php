<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
	<form action="skin.php" method="get" class="delete">
		<input type="hidden" name="key" value="delete"/>
		<table border="1" class="table remove">
          <tbody class="row1">
		    <tr class="row2">
				<th width="120"><?php echo $LANG['K'];?></th>
				<td><input type="text" name="k" name="k" value="<?php echo $SETTING['k'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['V'];?></th>
				<td><input type="text" name="v" value="<?php echo $SETTING['v'];?>" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['INFO'];?></th>
				<td><input type="text" name="info" value="<?php echo $SETTING['info'];?>" readonly="readonly"/></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:$('form').submit();">确认删除</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
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