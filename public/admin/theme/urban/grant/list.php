<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-grant">
      <div class="panel">
		<div class="panel-heading border"><?php echo $LANG['ADMIN_ADMIN_LIST']?></div>
		<div class="panel-body">
			<?php for($i=0;$i<count($users);$i++) {?>
				<div class="pull-left m25 mt10 mb10">
					<button type="button" class="adminGrant btn btn-primary btn-outline"><?php echo $users[$i]['uid'];?></button>
				</div>
			<?php }?>
		</div>
      </div>
    </div>
	<div></div>
<?php include dirname(__DIR__) . '/footer.php';?>
