<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div>
  <table border="1" class="table view">
      <tbody class="row1">
        <tr class="row2">
          <th width="120"><?php echo $LANG['ID'];?></th>
          <td width="300"><?php echo $CLIENT['id'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_ID'];?></th>
          <td><?php echo $CLIENT['clientId'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_SECRET'];?></th>
          <td><?php echo $CLIENT['clientSecret'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_NAME'];?></th>
          <td><?php echo $CLIENT['clientName'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_TYPE'];?></th>
          <td>
              <!-- IF CLIENT.clientType == "webApp" -->
              <?php if($CLIENT['clientType'] == "webApp") {?>
              WEB应用
              <!-- ELSEIF CLIENT.clientType == "jsApp" -->
              <?php } else if($CLIENT['clientType'] == "jsApp") {?>
              JS应用
              <!-- ELSEIF CLIENT.clientType == "desktopApp" -->
              <?php } else if($CLIENT['clientType'] == "desktopApp") {?>
              桌面应用
              <!-- ENDIF --><?php }?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_DESCRIBE'];?></th>
          <td><?php echo $CLIENT['clientDescribe'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['REDIRECT_URI'];?></th>
          <td><?php echo $CLIENT['redirectURI'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_SCOPE'];?></th>
          <td><?php echo $CLIENT['clientScope'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_LOCATION'];?></th>
          <td><?php echo $CLIENT['clientLocation'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_LOGOURI'];?></th>
          <td><?php echo $CLIENT['clientLogoUri'];?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_ISSHOW'];?></th>
          <td><!-- IF CLIENT.clientIsShow == "0" --><?php if($CLIENT['clientIsShow'] == "0") {?>否<!-- ELSEIF CLIENT.clientIsShow > "0" --><?php } else if($CLIENT['clientIsShow'] > "0") {?>是<!-- ENDIF --><?php }?></td>
        </tr>
        <tr class="row2">
          <th><?php echo $LANG['CLIENT_VISIBLE'];?></th>
          <td>
              <?php if($CLIENT['clientVisible'] == 0) {?>全部
              <?php } else if($CLIENT['clientVisible'] == 1) {?>教师
              <?php } else if($CLIENT['clientVisible'] == 2) {?>学生
              <?php } else if($CLIENT['clientVisible'] == 3) {?>其他
              <?php }?>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="tdcenter">
            <a href="client.php?key=tomodify&id=<?php echo $CLIENT['id'];?>">修改</a>
            <a href="javascript:void(0);" onclick="javascript:$.deleteClientById('<?php echo $CLIENT['id'];?>','<?php echo $CLIENT['clientId'];?>','<?php echo $CLIENT['clientName'];?>','<?php echo $CLIENT['clientSecret'];?>');">删除</a>
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