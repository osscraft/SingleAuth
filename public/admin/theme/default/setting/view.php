<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
  <table border="1" class="table view">
      <tbody class="row1">
        <tr class="row2">
          <th width="120"><?php echo $LANG['K'];?></th>
          <td width="300"><?php echo $SETTING['k'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['V'];?></th>
          <td><?php echo $SETTING['v'];?></td>
        </tr>
		<tr class="row2">
          <th><?php echo $LANG['INFO'];?></th>
          <td><?php echo $SETTING['info'];?></td>
        </tr>
        <tr>
          <td colspan="2" class="tdcenter">
            <a href="setting.php?key=tomodify&k=<?php echo $SETTING['k'];?>">修改</a>
            <a href="setting.php?key=todelete&k=<?php echo $SETTING['k'];?>">删除</a>
            <a href="javascript:history.go(-1);">返回</a>
          </td>
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