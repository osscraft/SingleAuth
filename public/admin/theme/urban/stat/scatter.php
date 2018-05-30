<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-statistics stat-scatter">
      <div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-heading border"> 浏览器分布
					<!-- 最高：<span class="max-browser text-primary bold no-margin"></span> -->
					<!-- <div class="pull-right form-group mnt6 ml15">
						<select class="stat-browser-d3 select-clientid form-control" id="browser-d3-clientId" name="browser-d3-clientId">
							<option value="">选择应用</option>
							<?php foreach ($clients as $c) {?>
							<option value="<?php echo $c['clientId']?>"><?php echo $c['clientName']?></option>
							<?php }?>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-browser-d3 select-date form-control" id="browser-d3-statDate" name="browser-d3-statDate">
							<option value="">日</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-browser-d3 select-month form-control" id="browser-d3-statMonth" name="browser-d3-statMonth">
							<option value="">月</option>
						</select>
					</div>
					<div class="pull-right form-group mnt6 ml15">
						<select class="stat-browser-d3 select-year form-control" id="browser-d3-statYear" name="browser-d3-statYear">
							<option value="">年</option>
						</select>
					</div> -->
				</div>
				<div class="panel-body">
				  	<div id="sequence"></div>
					<div id="legend"></div>
					<div class="stat-browser-d3 chart relative ps-container" style="float:left">
						  <div id="browser_d3">
						  </div>
						  <!--<div id="explanation" style="display: none;">
							<span id="percentage">0.314%</span><br>
							of visits begin with this sequence of pages
						  </div>-->
						  <!--<div id="sidebar">-->
						  <!--</div>-->
					</div>
				</div>
			</div>
		</div>
      </div>
    </div>
<?php include dirname(__DIR__) . '/footer.php';?>
