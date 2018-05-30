<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div class="view">
	<table border="1" class="table view">
	  <tbody class="row1">
	    <tr class="row2">
		  <th width="120"><?php echo $LANG['UID'];?></th>
		  <td width="300"><?php echo $USER['uid'];?></td>
	    </tr>
	    <tr class="row2">
		  <th><?php echo $LANG['USERNAME'];?></th>
		  <td><?php echo $USER['username'];?></td>
	    </tr>
	    <tr class="row2">
		  <th><?php echo $LANG['IS_ADMIN'];?></th>
		  <td><?php echo $USER['isAdmin'];?></td>
	    </tr>
	    <tr>
		  <td colspan="2" class="tdcenter"><a href="user.php?key=tomodify&uid=<?php echo $USER['uid'];?>">修改</a>&nbsp;<a href="user.php?key=todelete&uid=<?php echo $USER['uid'];?>">删除</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
	    </tr>
	  </tbody>
	</table>
</div>
<p></p>

   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>