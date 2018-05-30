<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p>
	<!-- IF PAGING -->
	<?php if(!empty($PAGING)) {?>
        <!-- BEGIN PAGING.pages -->
        <?php foreach ($PAGING['pages'] as $i => $p) {?>
            <!-- IF page == p -->
            <?php if($p['p'] == $p['page']) {?>
               <?php echo $p['text'];?>&nbsp;
            <!-- ELSE -->
            <?php } else {?>
                <a href="<?php echo $p['url'];?>"><?php echo $p['text'];?></a>&nbsp;
            <!-- ENDIF -->
            <?php }?>
        <!-- END -->
        <?php }?>&nbsp;<span>共</span><?php echo $PAGING['page']['pageCount'];?><span>页</span><!--&nbsp;&nbsp;&nbsp;&nbsp;<input id="searchClientId" name="searchClientId" value="输入客户端标识符" maxLength="32" style="color: #9C9C9C;"><input id="searchClientIdBtn" type="button" style="width:40px;" value="查找"/>-->
    <!-- ENDIF -->
    <?php }?>
</p>
<div>
	<table border="1" class="table list">
		<thead>
			<tr class="row0">
				<th width="120"><?php echo $LANG['UID'];?></th>
				<th width="120"><?php echo $LANG['USERNAME'];?></th>
				<th width="80"><?php echo $LANG['IS_ADMIN'];?></th>
				<th width="120"><?php echo $LANG['OPERATE'];?></th>
			</tr>
		</thead>
		<!-- BEGIN USERS -->
       	<?php foreach ($USERS as $i => $u) {?>
		<tbody class="row1">
			<tr class="row2" onclick="javascript:window.location.href='user.php?key=view&uid=<?php echo $u['uid'];?>';">
				<td><?php echo $u['uid'];?></td>
				<td><?php echo $u['username'];?></td>
				<td><?php echo $u['isAdmin'];?></td>
				<td class="tdcenter"><a href="user.php?key=view&uid=<?php echo $u['uid'];?>">查看</a>&nbsp;<a href="user.php?key=tomodify&uid=<?php echo $u['uid'];?>">修改</a>&nbsp;<a href="user.php?key=todelete&uid=<?php echo $u['uid'];?>">删除</a></td>
			</tr>
		</tbody>
		    <!-- END -->
        <?php }?>
	</table>
</div>
<p>
	<!-- IF PAGING -->
	<?php if(!empty($PAGING)) {?>
        <!-- BEGIN PAGING.pages -->
        <?php foreach ($PAGING['pages'] as $i => $p) {?>
            <!-- IF page == p -->
            <?php if($p['p'] == $p['page']) {?>
               <?php echo $p['text'];?>&nbsp;
            <!-- ELSE -->
            <?php } else {?>
                <a href="<?php echo $p['url'];?>"><?php echo $p['text'];?></a>&nbsp;
            <!-- ENDIF -->
            <?php }?>
        <!-- END -->
        <?php }?>&nbsp;<span>共</span><?php echo $PAGING['page']['pageCount'];?><span>页</span><!--&nbsp;&nbsp;&nbsp;&nbsp;<input id="searchClientId" name="searchClientId" value="输入客户端标识符" maxLength="32" style="color: #9C9C9C;"><input id="searchClientIdBtn" type="button" style="width:40px;" value="查找"/>-->
    <!-- ENDIF -->
    <?php }?>
</p>

   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>