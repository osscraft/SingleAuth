<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
	<form action="skin.php" method="get" class="create">
		<input type="hidden" name="key" value="create"/>
		<table border="1" class="table create">
          <tbody class="row1">
		    <tr class="row2">
				<th width="120"><?php echo $LANG['K'];?></th>
				<td><input type="text" id="k" name="k" value="" maxLength="32"/></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['V'];?></th>
				<td><input id="v" type="text" name="v" value="" /></td>
			</tr>
			<tr class="row2">
				<th><?php echo $LANG['INFO'];?></th>
				<td><input id="info" type="text" name="info" value="" /></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter">
					<a href="javascript:submitSetting(document.getElementsByTagName('form')[0]);">创建</a>
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