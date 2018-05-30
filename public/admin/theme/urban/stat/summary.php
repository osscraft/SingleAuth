<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-statistics stat-summary">
      <div class="row">
		<div class="col-md-7">
			<div class="panel">
				<div class="panel-heading border"> 实时在线用户数
					<!-- <span class="count-online text-primary bold no-margin"></span> -->
				</div>
				<div class="panel-body">
					<div class="stat-sm-online chart-sm">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="panel">
				<div class="panel-heading border"> <a href="/admin/stat/scatter.php">浏览器分布</a> 
					<!-- 最高：<span class="max-browser text-primary bold no-margin"></span> -->
				</div>
				<div class="panel-body">
					<div class="stat-sm-browser chart-sm">
					</div>
				</div>
			</div>
		</div>
      </div>
      <div class="row">
		<!-- <div class="col-md-6">
			<div class="panel">
				<div class="panel-heading border"> 浏览器分布 
				</div>
				<div class="panel-body">
					<div class="stat-sm-s chart-sm">
					</div>
				</div>
			</div>
		</div> -->
		<div class="col-md-6">
			<div class="panel">
				<div class="panel-heading border"> <a href="/admin/stat/top-client.php">应用排名</a>
					<!-- 最高：<span class="max-browser text-primary bold no-margin"></span> -->
				</div>
				<div class="panel-body">
					<div class="stat-sm-clienttop chart-sm">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel">
				<div class="panel-heading border"> <a href="/admin/stat/top-user.php">用户排名</a>
					<!-- 最高：<span class="max-browser text-primary bold no-margin"></span> -->
				</div>
				<div class="panel-body">
					<div class="stat-sm-usertop chart-sm">
					</div>
				</div>
			</div>
		</div>
      </div>
    </div>
<?php include dirname(__DIR__) . '/footer.php';?>
