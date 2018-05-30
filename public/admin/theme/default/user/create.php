<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div class="create">
  <form action="user.php" method="get">
    <input type="hidden" name="key" value="create"/>
    <table border="1" class="table create">
        <tbody class="row1">
          <tr class="row2">
        <td width="120"><?php echo $LANG['UID'];?></td>
        <td><!-- IF USER --><?php if(!empty($USER)) {?><input type="text" name="uid" value="<?php echo $USER['uid'];?>" readonly="readonly"/><!-- ELSE --><?php } else {?><input type="text" name="uid" value="" maxLength="50" /><!-- ENDIF --><?php }?></td>
      </tr>
          <tr class="row2">
        <td><?php echo $LANG['USERNAME'];?></td>
        <td><!-- IF USER --><?php if(!empty($USER)) {?><input type="text" name="username" value="<?php echo $USER['username'];?>" readonly="readonly"/><!-- ELSE --><?php } else {?><input type="text" name="username" value="" maxLength="20" /><!-- ENDIF --><?php }?></td>
      </tr>
          <tr class="row2">
        <td><?php echo $LANG['IS_ADMIN'];?></td>
        <td><!-- IF USER --><?php if(!empty($USER)) {?><input type="text" name="isAdmin" value="<?php echo $USER['isAdmin'];?>" readonly="readonly"/><!-- ELSE --><?php } else {?><input type="text" name="isAdmin" value="" maxLength="1" /><!-- ENDIF --><?php }?></td>
      </tr>
      <tr>
        <td colspan="2" class="tdcenter"><!-- IF USER --><?php if(!empty($USER)) {?><a href="javascript:;" onclick="javascript:submitUser(document.getElementsByTagName('form')[0]);">创建</a><!-- ELSE --><?php } else {?><!--<a id="verify" href="javascript:;" onclick="javascript:alert('正确的用户名');">验证</a>&nbsp;--><a id="create" href="javascript:;" onclick="javascript:submitUser(document.getElementsByTagName('form')[0]);">创建</a><!-- ENDIF --><?php }?>&nbsp;<a href="javascript:;" onclick="javascript:history.go(-1);">返回</a></td>
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