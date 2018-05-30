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
        <input id="searchClientId" name="searchClientId" value="输入客户端标识符或名称" maxLength="32" style="color: #9C9C9C;"><input id="searchClientIdBtn" type="button" style="width:40px;" value="查找"/>
</p>
<div>
	<table border="1" class="table list">
		<thead>
			<tr class="row0">
				<th width="40"><?php echo $LANG['ID'];?></th>
				<th width="140"><?php echo $LANG['CLIENT_ID'];?></th>
				<!--<td width="100"><?php echo $LANG['CLIENT_SECRET'];?>{LANG.CLIENT_SECRET}</td>-->
				<th width="140"><?php echo $LANG['CLIENT_NAME'];?></th>
				<th width="100"><?php echo $LANG['CLIENT_TYPE'];?></th>
				<th width="80"><?php echo $LANG['CLIENT_ISSHOW'];?></th>
				<th width="180"><?php echo $LANG['CLIENT_LOCATION'];?></th>
				<!--<th width="100"><?php echo $LANG['CLIENT_SCOPE'];?>{LANG.CLIENT_SCOPE}</th>-->
				<th width="140"><?php echo $LANG['OPERATE'];?></th>
				
			</tr>
		</thead>
		<!-- BEGIN CLIENTS -->
        <?php foreach ($CLIENTS as $i => $c) {?>
		<tbody class="row1">
			<tr class="row2"><!-- onclick="javascript:window.location.href='client.php?key=view&id={id}';"-->
				<td><div class="clip" style="width:40px; text-align:center;"><?php echo $c['id'];?></div></td>
				<td><div class="clip" style="width:160px; " title="<?php echo $c['clientId'];?>"><?php echo $c['clientId'];?></div></td>
				<!--<td><?php echo $c['clientSecret'];?></td>-->
				<td><div class="clip" style="width:140px; " title="<?php echo $c['clientName'];?>"><?php echo $c['clientName'];?></div></td>
				<td class="tdcenter"><!-- IF clientType == "webApp" --><?php if($c['clientType'] == "webApp") {?>
				    <span>WEB应用</span>
				    <!-- ELSEIF clientType == "jsApp" -->
				    <?php } else if($c['clientType'] == "jsApp") {?>
				    <span>JS应用</span>
				    <!-- ELSEIF clientType == "desktopApp" -->
				    <?php } else if($c['clientType'] == "desktopApp") {?>
				    <span>桌面应用</span>
				    <!-- ENDIF --><?php }?></td>
				<td><div class="clip" style="width:80px; text-align:center;"><!-- IF clientIsShow == "0" --><?php if($c['clientIsShow'] == "0") {?>否<!-- ELSEIF clientIsShow > "0" --><?php } else if($c['clientIsShow'] > "0") {?>是<!-- ENDIF --><?php }?></div></td>
				<td><div class="clip" style="width:180px; " title="<?php echo $c['clientLocation'];?>"><?php echo $c['clientLocation'];?></div></td>
				<!--<td><?php echo $c['clientScope'];?></td>-->
				<td class="tdcenter">
					<a href="client.php?key=view&id=<?php echo $c['id'];?>">查看</a>
					<a href="stat.php?key=client&id=<?php echo $c['id'];?>">统计</a>
					<a href="client.php?key=tomodify&id=<?php echo $c['id'];?>">修改</a>
					<a href="javascript:void(0);" onclick="javascript:$.deleteClientById('<?php echo $c['id'];?>','<?php echo $c['clientId'];?>','<?php echo $c['clientName'];?>','<?php echo $c['clientSecret'];?>');">删除</a>
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
        <?php }?>&nbsp;<span>共</span><?php echo $PAGING['page']['pageCount'];?><span>页</span><!--&nbsp;&nbsp;&nbsp;&nbsp;<input id="searchClientId" name="searchClientId" value="输入客户端标识符" maxLength="32" style="color: #9C9C9C;"><input id="searchClientIdBtn" type="button" style="width:40px;" value="查找"/>-->
    <!-- ENDIF -->
    <?php }?>
</p>

   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>