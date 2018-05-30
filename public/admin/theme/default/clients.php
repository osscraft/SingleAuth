<!-- INCLUDE header.html -->
<?php include __DIR__ . '/header.php';?>
   <div class="body">
    <div class="body_resize">
<!-- IF list -->
<?php if(!empty($list)) {?>
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
	<table border="1" class="list">
		<thead>
			<tr class="row0">
				<th width="40"><?php echo $LANG['ID'];?></th>
				<th width="160"><?php echo $LANG['CLIENT_ID'];?></th>
				<!--<td width="100"><?php echo $LANG['CLIENT_SECRET'];?>{LANG.CLIENT_SECRET}</td>-->
				<th width="140"><?php echo $LANG['CLIENT_NAME'];?></th>
				<th width="100"><?php echo $LANG['CLIENT_TYPE'];?></th>
				<th width="80"><?php echo $LANG['CLIENT_ISSHOW'];?></th>
				<th width="180"><?php echo $LANG['CLIENT_LOCATION'];?></th>
				<!--<th width="100"><?php echo $LANG['CLIENT_SCOPE'];?>{LANG.CLIENT_SCOPE}</th>-->
				<th width="120"><?php echo $LANG['OPERATE'];?></th>
				
			</tr>
		</thead>
		<!-- BEGIN CLIENTS -->
		<tbody class="row1">
			<tr class="row2"><!-- onclick="javascript:window.location.href='client.php?key=view&id={id}';"-->
			<span >
				<td><div class="clip" style="width:40px; text-align:center;">{id}</div></td>
				<td><div class="clip" style="width:160px; ">{clientId}</div></td>
				<!--<td>{clientSecret}</td>-->
				<td><div class="clip" style="width:140px; ">{clientName}</div></td>
				<td class="tdcenter"><!-- IF clientType == "webApp" -->
				    <span>WEB应用</span>
				    <!-- ELSEIF clientType == "jsApp" -->
				    <span>JS应用</span>
				    <!-- ELSEIF clientType == "desktopApp" -->
				    <span>桌面应用</span>
				    <!-- ENDIF --></td>
				<td><div class="clip" style="width:80px; text-align:center;"><!-- IF clientIsShow == "0" -->否<!-- ELSEIF clientIsShow > "0" -->是<!-- ENDIF --></div></td>
				<td><div class="clip" style="width:180px; ">{clientLocation}</div></td>
				<!--<td>{clientScope}</td>-->
				<td class="tdcenter"><a href="javascript:void(0);" onclick="javascript:$.viewClientById('{id}');">查看</a>&nbsp;<a href="javascript:void(0);" onclick="javascript:$.modifyClientById('{id}');">修改</a>&nbsp;<a href="javascript:void(0);" onclick="javascript:$.deleteClientById('{id}','{clientId}','{clientName}','{clientSecret}');">删除</a></td>
			</span>
			</tr>
		</tbody>
		 <!-- END -->
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
<!-- ELSEIF view -->
<?php } else if(!empty($view)) {?>
<p></p>
<div>
	<table border="1" class="view">
      <tbody class="row1">
		<tr class="row2">
			<th width="120">{LANG.ID}</th>
			<td width="300">{CLIENT.id}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_ID}</th>
			<td>{CLIENT.clientId}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_SECRET}</th>
			<td>{CLIENT.clientSecret}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_NAME}</th>
			<td>{CLIENT.clientName}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_TYPE}</th>
			<td><!-- IF CLIENT.clientType == "webApp" -->
			    WEB应用
			    <!-- ELSEIF CLIENT.clientType == "jsApp" -->
			    JS应用
			    <!-- ELSEIF CLIENT.clientType == "desktopApp" -->
			    桌面应用
			    <!-- ENDIF --></td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_DESCRIBE}</th>
			<td>{CLIENT.clientDescribe}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.REDIRECT_URI}</th>
			<td>{CLIENT.redirectURI}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_SCOPE}</th>
			<td>{CLIENT.clientScope}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_LOCATION}</th>
			<td>{CLIENT.clientLocation}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_LOGOURI}</th>
			<td>{CLIENT.clientLogoUri}</td>
		</tr>
		<tr class="row2">
			<th>{LANG.CLIENT_ISSHOW}</th>
			<td><!-- IF CLIENT.clientIsShow == "0" -->否<!-- ELSEIF CLIENT.clientIsShow > "0" -->是<!-- ENDIF --></td>
		</tr>
		<tr>
			<td colspan="2" class="tdcenter"><a href="javascript:void(0);" onclick="javascript:$.modifyClientById('{CLIENT.id}');">修改</a>&nbsp;<a href="javascript:void(0);" onclick="javascript:$.deleteClientById('{CLIENT.id}','{CLIENT.clientId}','{CLIENT.clientName}','{CLIENT.clientSecret}');">删除</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
		</tr>
      </tbody>
	</table>
</div>
<p></p>
<!-- ELSEIF tomodify -->
<?php } else if(!empty($tomodify)) {?>
<p></p>
<div>
	<form action="client.php" method="get" class="modify">
		<input type="hidden" name="key" value="modify"/>
		<table border="1">
          <tbody class="row1">
			<tr class="row2">
				<th width="120">{LANG.ID}</th>
				<td>{CLIENT.id}<input type="hidden" id="id" name="id" value="{CLIENT.id}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_ID}</th>
				<td>{CLIENT.clientId}<input type="hidden" id="clientId" name="clientId" value="{CLIENT.clientId}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_SECRET}</th>
				<td><span id="clientSecretText" name="clientSecretText">{CLIENT.clientSecret}</span><input id="clientSecret" type="hidden" name="clientSecret" value="{CLIENT.clientSecret}" readonly="readonly"/><input type="button" value="随机生成" onclick="javascript:$.geneClientSecret();"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_NAME}</th>
				<td><input type="text" id="clientName" name="clientName" value="{CLIENT.clientName}" maxLength="50"/><label>50字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_TYPE}</th>
				<td><select id="clientType" name="clientType" size="1"　readonly="readonly"><!-- IF CLIENT.clientType == "webApp" --><option value="webApp" selected="true"><!-- ELSE --><option value="webApp" ><!-- ENDIF -->WEB应用</option><!-- IF CLIENT.clientType == "jsApp" --><option value="jsApp" selected="true" ><!-- ELSE --><option value="jsApp" ><!-- ENDIF -->JS应用</option><!-- IF CLIENT.clientType == "desktopApp" --><option value="desktopApp" selected="true" ><!-- ELSE --><option value="desktopApp" ><!-- ENDIF -->桌面应用</option></select></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_DESCRIBE}</th>
				<td><textarea id="clientDescribe" name="clientDescribe">{CLIENT.clientDescribe}</textarea><!--<input type="text" name="clientDescribe" value="{CLIENT.clientDescribe}" />--><label>1000字符以内</label></td>
			</tr>
			<tr class="row2">
				<th >{LANG.REDIRECT_URI}</th>
				<td><input type="text" id="redirectURI" name="redirectURI" value="{CLIENT.redirectURI}" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_SCOPE}</th>
				<td><input type="text" id="clientScope" name="clientScope" value="{CLIENT.clientScope}" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_LOCATION}</th>
				<td><input type="text" id="clientLocation" name="clientLocation" value="{CLIENT.clientLocation}" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_LOGOURI}</th>
				<td><input type="text" id="clientLogoUri" name="clientLogoUri" value="{CLIENT.clientLogoUri}" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_ISSHOW}</th>
				<td><input type="text" id="clientIsShow" name="clientIsShow" value="{CLIENT.clientIsShow}" maxLength="3"/><label title="0为不在首页显示，大于0的可进行从大到小排序">0-255的数值</label></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:void(0);" onclick="javascript:$.submitClient();">确认修改</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
			</tr>
          </tbody>
		</table>
	</form>
</div>
<p></p>
<!-- ELSEIF modify -->
<?php } else if(!empty($modify)) {?>
<p></p>
<div align="center">
    <!-- IF SUCCESS -->
	<h2>修改成功！</h2>
    <!-- ELSE -->
	<h2>修改失败！</h2>
    <!-- ENDIF -->
	<p><a href="javascript:history.go(-1);">返回</a></p>
</div>
<p></p>
<!-- ELSEIF todelete -->
<?php } else if(!empty($todelete)) {?>
<p></p>
<div>
	<form action="client.php" method="get" class="delete">
		<input type="hidden" name="key" value="delete"/>
		<table border="1">
          <tbody class="row1">
		    <tr class="row2">
				<th width="120">{LANG.ID}</th>
				<td><input type="text" name="id" name="id" value="{CLIENT.id}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_ID}</th>
				<td><input type="text" name="clientId" value="{CLIENT.clientId}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_SECRET}</th>
				<td><input type="text" name="clientSecret" value="{CLIENT.clientSecret}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_NAME}</th>
				<td><input type="text" name="clientName" value="{CLIENT.clientName}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_TYPE}</th>
				<td><!-- IF CLIENT.clientType == "webApp" -->
				    <input type="text" name="clientType" value="WEB应用" readonly="readonly" />
				    <!-- ELSEIF CLIENT.clientType == "jsApp" -->
				    <input type="text" name="clientType" value="JS应用" readonly="readonly" />
				    <!-- ELSEIF CLIENT.clientType == "desktopApp" -->
				    <input type="text" name="clientType" value="桌面应用" readonly="readonly" />
				    <!-- ENDIF -->
				</td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_DESCRIBE}</th>
				<td><textarea name="clientDescribe" readonly="readonly">{CLIENT.clientDescribe}</textarea><!--<input type="text" name="clientDescribe" value="{CLIENT.clientDescribe}" readonly="readonly"/>--></td>
			</tr>
			<tr class="row2">
				<th >{LANG.REDIRECT_URI}</th>
				<td><input type="text" name="redirectURI" value="{CLIENT.redirectURI}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_SCOPE}</th>
				<td><input type="text" name="clientScope" value="{CLIENT.clientScope}" readonly="readonly"/></td>
			</tr>
			<tr>
				<th>{LANG.CLIENT_LOCATION}</th>
				<td><input type="text" name="clientLocation" value="{CLIENT.clientLocation}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_LOGOURI}</th>
				<td><input type="text" name="clientLogoUri" value="{CLIENT.clientLogoUri}" readonly="readonly"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_ISSHOW}</th>
				<td><input type="text" name="clientIsShow" value="{CLIENT.clientIsShow}" readonly="readonly"/></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:void(0);" onclick="javascript:$('form').submit();">确认删除</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
			</tr>
		  </tbody>
		</table>
	</form>
</div>
<p></p>
<!-- ELSEIF delete -->
<?php } else if(!empty($delete)) {?>
<p></p>
<div align="center">
    <!-- IF SUCCESS -->
	<h2>删除成功！</h2>
    <!-- ELSE -->
	<h2>删除失败！</h2>
    <!-- ENDIF -->
	<p><a href="client.php">返回</a></p>
</div>
<p></p>
<!-- ELSEIF tocreate -->
<?php } else if(!empty($tocreate)) {?>
<p></p>
<div>
	<form action="client.php" method="get" class="create">
		<input type="hidden" name="key" value="create"/>
		<table border="1">
          <tbody class="row1">
		    <tr class="row2">
				<th width="120">{LANG.CLIENT_ID}</th>
				<td><input type="text" id="clientId" name="clientId" value="" maxLength="32"/><label>32字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_SECRET}</th>
				<td><span id="clientSecretText" name="clientSecretText"></span><input id="clientSecret" type="hidden" name="clientSecret" value="{CLIENT.clientSecret}" readonly="readonly"/><input type="button" value="随机生成" onclick="javascript:$.geneClientSecret();"/></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_NAME}</th>
				<td><input type="text" id="clientName" name="clientName" value="" maxLength="50" /><label>50字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_TYPE}</th>
				<td><select id="clientType" name="clientType" size="1"　readonly="readonly"><option value="webApp" selected="true">WEB应用</option><option value="jsApp" >JS应用</option><option value="desktopApp" >桌面应用</option></select></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_DESCRIBE}</th>
				<td><textarea id="clientDescribe" name="clientDescribe" maxLength="1000"></textarea><!--<input type="text" name="clientDescribe" value="" maxLength="1000" />--><label>1000字符以内</label></td>
			</tr>
			<tr class="row2">
				<th >{LANG.REDIRECT_URI}</th>
				<td><input type="text" id="redirectURI" name="redirectURI" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_SCOPE}</th>
				<td><input type="text" id="clientScope" name="clientScope" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_LOCATION}</th>
				<td><input type="text" id="clientLocation" name="clientLocation" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_LOGOURI}</th>
				<td><input type="text" id="clientLogoUri" name="clientLogoUri" value="" maxLength="255"/><label>255字符以内</label></td>
			</tr>
			<tr class="row2">
				<th>{LANG.CLIENT_ISSHOW}</th>
				<td><input type="text" id="clientIsShow" name="clientIsShow" value="0"/><label title="0为不在首页显示，大于0的可进行从大到小排序">0-255的数值</label></td>
			</tr>
			<tr>
				<td colspan="2" class="tdcenter"><a href="javascript:void(0);" onclick="javascript:$.submitClient();">创建</a>&nbsp;<a href="javascript:history.go(-1);">返回</a></td>
			</tr>
		  </tbody>
		</table>
	</form>
</div>
<p></p>
<!-- ELSEIF create -->
<?php } else if(!empty($create)) {?>
<p></p>
<div align="center">
    <!-- IF SUCCESS -->
	<h2>新建成功！</h2>
    <!-- ELSE -->
	<h2>新建失败！</h2>
    <!-- ENDIF -->
	<p><a href="javascript:history.go(-1);">返回</a></p>
</div>
<p></p>
<!-- ENDIF -->
<?php }?>
   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include __DIR__ . '/footer.php';?>
