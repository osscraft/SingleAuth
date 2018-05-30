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
        <?php }?>&nbsp;<span>共</span><?php echo $PAGING['page']['pageCount'];?><span>页</span>&nbsp;&nbsp;&nbsp;&nbsp;
    <!-- ENDIF -->
    <?php }?>
        <!--<input id="searchClientId" name="searchClientId" value="输入客户端标识符或名称" maxLength="32" style="color: #9C9C9C;"><input id="searchClientIdBtn" type="button" style="width:40px;" value="查找"/>-->
</p>
<div>
	<table border="1" class="table list">
		<thead>
			<tr class="row0">
				<th width="180"><?php echo $LANG['K'];?></th>
				<th width="300"><?php echo $LANG['V'];?></th>
				<th width="300"><?php echo $LANG['INFO'];?></th>
				<th width="120"><?php echo $LANG['OPERATE'];?></th>
			</tr>
		</thead>
		<!-- BEGIN SETTING -->
        <?php foreach ($SETTING as $i => $c) {?>
		<tbody class="row1">
			<tr class="row2"><!-- onclick="javascript:window.location.href='client.php?key=view&id={id}';"-->
				<td><div class="clip" style="width:180px;"><?php echo $c['k'];?></div></td>
				<td><div class="clip" style="width:300px; " title="<?php echo $c['v'];?>"><?php echo $c['v'];?></div></td>
				<td><div class="clip" style="width:300px; " title="<?php echo $c['info'];?>"><?php echo $c['info'];?></div></td>
				<td class="tdcenter">
					<a href="setting.php?key=view&k=<?php echo $c['k'];?>">查看</a>
					<a href="setting.php?key=tomodify&k=<?php echo $c['k'];?>">修改</a>
					<a href="setting.php?key=todelete&k=<?php echo $c['k'];?>">删除</a>
				</td>
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
        <?php }?>&nbsp;<span>共</span><?php echo $PAGING['page']['pageCount'];?><span>页</span>
    <!-- ENDIF -->
    <?php }?>
</p>

   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>