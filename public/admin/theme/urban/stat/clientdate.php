<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-statistics stat-clientdate">
      <div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-heading border"> 访问流量
					<!-- 最高：<span class="max-browser text-primary bold no-margin"></span> -->
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-clientdate select-clientid form-control" id="clientdate-clientId" name="clientdate-clientId">
							<option value="">选择应用</option>
							<?php foreach ($clients as $c) {?>
							<option value="<?php echo $c['clientId']?>"><?php echo $c['clientName']?></option>
							<?php }?>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-clientdate select-date form-control" id="clientdate-statDate" name="clientdate-statDate">
							<option value="">日</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-clientdate select-month form-control" id="clientdate-statMonth" name="clientdate-statMonth">
							<option value="">月</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-clientdate select-year form-control" id="clientdate-statYear" name="clientdate-statYear">
							<option value="">年</option>
						</select>
					</div>
				</div>
				<div class="panel-body">
					<div class="stat-clientdate chart">
					</div>
				</div>
			</div>
		</div>
      </div>
    </div>
<?php include dirname(__DIR__) . '/footer.php';?>
