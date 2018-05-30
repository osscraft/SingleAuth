<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-skin checkbo">
    	<div class="row">
    		<div class="col-md-4">
		      	<div class="panel">
					<div class="panel-heading border"> 设置首页皮肤
					</div>
			        <div class="panel-body skin-main">
        				<?php foreach ($mains as $m) {?>
						<label class="cb-radio cb-lg<?php echo $m == $main ? ' checked' : '';?>">
							<input class="skin-main" type="radio" name="skin-main" value="<?php echo "$m";?>"><?php echo "$m";?>
						</label><br>
        				<?php }?>
        				<button type="button" class="skin-main btn btn-primary">确定</button>
			        </div>
		      	</div>
    		</div>
    		<div class="col-md-4">
		      	<div class="panel">
					<div class="panel-heading border"> 设置管理皮肤
					</div>
			        <div class="panel-body skin-admin">
        				<?php foreach ($admins as $a) {?>
						<label class="cb-radio cb-lg<?php echo $a == $admin ? ' checked' : '';?>">
							<input class="skin-admin" type="radio" name="skin-admin" value="<?php echo "$a";?>"><?php echo "$a";?>
						</label><br>
        				<?php }?>
        				<button type="button" class="skin-admin btn btn-primary">确定</button>
			        </div>
		      	</div>
    		</div>
    	</div>
    </div>
<?php include dirname(__DIR__) . '/footer.php';?>
