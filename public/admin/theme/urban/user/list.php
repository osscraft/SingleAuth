<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
    <div class="main-content admin-user">
      <div class="panel">
        <div class="panel-body">
          <table class="table table-bordered bordered table-striped table-condensed datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info" >
            <thead>
				<tr role="row">
					<th class="sorting_asc"><?php echo $LANG['UID'];?></th>
					<th class="sorting"><?php echo $LANG['USERNAME'];?></th>
					<th class="sorting"><?php echo $LANG['IS_ADMIN'];?></th>
					<th class="">操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
          </table>
        </div>
      </div>
    </div>
	<!--<div id="myModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<form id="user" class="form-horizontal">
						<legend>TestForm</legend>
						<div class="control-group">
							<label for="uid" style="display: inline-block;vertical-align: middle;">UID:</label>
							<input name="uid" class="form-control" />
						</div>
						<div>
							<label for="username">USERNAME:</label>
							<input name="username" class="form-control"/>
						</div>
						<div>
							<label for="isAdmin">ISADMIN:</label>
							<input name="isAdmin" class="form-control"/>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary ok" id="adds">添加</button>
					<button type="button" class="btn btn-primary ok" id="edits">保存</button>
					<button type="button" class="btn btn-primary close">取消</button>
				</div>
			</div>
		</div>
	</div>-->
<?php include dirname(__DIR__) . '/footer.php';?>
