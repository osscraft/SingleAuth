<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-statistics stat-top-user">
      <div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-heading border"> 用户排名
					<!-- 最高：<span class="max-browser text-primary bold no-margin"></span> -->
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-top-user select-date form-control" id="top-user-statDate" name="top-user-statDate">
							<option value="">日</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-top-user select-month form-control" id="top-user-statMonth" name="top-user-statMonth">
							<option value="">月</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-top-user select-year form-control" id="top-user-statYear" name="top-user-statYear">
							<option value="">年</option>
						</select>
					</div>
				</div>
				<div class="panel-body">
					<div class="stat-top-user chart">
					</div>
				</div>
			</div>
		</div>
      </div>
    </div>
<?php include dirname(__DIR__) . '/footer.php';?>
