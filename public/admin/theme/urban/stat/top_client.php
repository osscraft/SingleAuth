<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-statistics stat-top-client">
      <div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-heading border"> 应用排名
					<!-- 最高：<span class="max-browser text-primary bold no-margin"></span> -->
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-top-client select-date form-control" id="top-client-statDate" name="top-client-statDate">
							<option value="">日</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-top-client select-month form-control" id="top-client-statMonth" name="top-client-statMonth">
							<option value="">月</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-top-client select-year form-control" id="top-client-statYear" name="top-client-statYear">
							<option value="">年</option>
						</select>
					</div>
				</div>
				<div class="panel-body">
					<div class="stat-top-client chart">
					</div>
				</div>
			</div>
		</div>
      </div>
    </div>
<?php include dirname(__DIR__) . '/footer.php';?>
