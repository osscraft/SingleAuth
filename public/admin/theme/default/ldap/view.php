<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div class="view">
  <table border="1" class="table view">
    <tbody class="row1">
      <tr class="row2">
      <th width="120"><?php echo $LANG['HOST'];?></th>
      <td width="300"><?php echo $LDAPCONFIG['host'];?></td>
    </tr>
      <tr class="row2">
      <th><?php echo $LANG['BASE_DN'];?></th>
      <td><?php echo $LDAPCONFIG['baseDN'];?></td>
    </tr>
      <tr class="row2">
      <th><?php echo $LANG['ROOT_DN'];?></th>
      <td><?php echo $LDAPCONFIG['rootDN'];?></td>
    </tr>
      <tr class="row2">
      <th><?php echo $LANG['ROOT_PW'];?></th>
      <td><?php echo $LDAPCONFIG['rootPW'];?></td>
    </tr>
    <tr>
      <td colspan="2" class="tdcenter"><a href="ldapConfig.php?key=tomodify">修改<a/></td>
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