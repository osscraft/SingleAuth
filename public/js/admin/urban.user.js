var User = Admin.User = Urban.User = {};

User.datatable = null;
User.dtapi = null;
User.dtoption = {
    language: {
        "url": "/lib/datatables/plug-ins/i18n/Chinese.json"
    }
};
User.init = function() {
	User.datatable = $('.datatable').dataTable($.extend(User.dtoption, {
    	sAjaxSource : '/admin/ajax/user/query.json',
        columns: [{
        	class: "align-middle",
            "data": "uid"
        }, {
        	class: "align-middle",
            "data": "username"
        }, {
        	class: "align-middle",
            "data": "isAdmin"
        }, {
        	width : "110px",
			class: "align-middle",
            "orderable": false,
            "data": null,
            "defaultContent": Client.Html.operation()
        }],
        order: [
            [0, 'asc']
        ]
    })).one('draw.dt', function() {
		$('.toolbar').html(User.Html.operationAdd());
		User.listenOperation();
    }).on('click', 'tbody tr', function () {
    	//User.selectRow(this);
    });
	User.dtapi = User.datatable.api();
};
User.selectRow = function(row) {
    if ( $(row).hasClass('selected') ) {
        $(row).removeClass('selected');
        $('#remove').addClass('disabled');
        $('#edit').addClass('disabled');
    } else {
        User.datatable.$('tr.selected').removeClass('selected');
        $(row).addClass('selected');
        $('#remove').removeClass('disabled');
        $('#edit').removeClass('disabled');
    }
};
User.listenOperation = function() {
	User.datatable.on('click', 'tbody td a.edit', function (e) {
		var row = User.datatable.fnGetData($(this).parents('tr'));
		bootbox.dialog({
			message: User.Html.edit(row),
    		title:'修改用户',
			buttons: {
				success: {
					label:"保存",
					className: "btn-success",
					callback:User.fnUpdOk
				},
				cancle: {
					label:"取消",
					callback:User.fnUpdCancel
				}
			}
		})
		User.optionEditForm();
    	//User.addRowSelection(this);
    }).on('click', 'tbody td a.remove', function () {
		var row = User.datatable.fnGetData($(this).parents('tr'));
		bootbox.dialog({
            message: User.Html.del(row),
            title:'删除用户',
            buttons: [{
                label: '取消',
                callback: User.fnDelCancel
            }, {
                label: '确定',
                callback: User.fnDelOk
            }]
        });
    }).on('click', 'tbody td a.view', function (e) {
    	var row = User.datatable.fnGetData($(this).parents('tr'));
        bootbox.dialog({
    		message: User.Html.view(row),
    		title:'查看用户'
    	});
    })
    $('.new').click(function() {
    	bootbox.dialog({
    		message:User.Html.add(),
    		locale:'zh_CN',
    		title:'新增用户',
			buttons: {
				success: {
					label:"保存",
					className: "btn-success",
					callback:User.fnAddOk
				},
				cancle: {
					label:"取消",
					callback:User.fnAddCancel
				}
			}
    	});
		User.optionEditForm();
    });
};
// callbacks
User.fnAddCancel = function(e) {
    // nothing to do
};
User.fnAddOk = function(e) {
    if(User.validateAdd()) {
        // TODO add new client
        User.add({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                User.dtapi.ajax.reload();
            } else {
                User.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
User.fnUpdCancel = function(e) {
    // nothing to do
};
User.fnUpdOk = function(e) {
    if(User.validateUpd()) {
        // TODO modify client
        User.upd({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                User.dtapi.ajax.reload();
            } else {
                User.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
// deprecated
User.fnDelCancel = function(e) {
    // nothing to do
};
User.fnDelOk = function(result) {
    if(User.validateDel()) {
        // TODO modify client
        User.del({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                User.dtapi.ajax.reload();
            } else {
                User.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
User.optionAddForm = function() {
	$('.form-group input, form-group select').on('focus', function() {
    	$(this).closest('.form-group').removeClass('has-error');
    });
};
User.optionEditForm = function() {
    User.optionAddForm();
};
User.noticeValidation = function(selector) {// scroll to and class change selector
    $('.bootbox').scrollTo(selector, {offsetTop:0}, function() {
        App.Alt.flash(selector, 1, 0.1, 'class', 'form-group has-error', {attr:true});
    });
};
User.validateAdd = function(el) {
    var uid = $("#uid").val();
    var username = $("#username").val();
    var isAdmin = $("#isAdmin").val();
    if(!uid) {
        User.noticeValidation($('#uid').closest('.form-group'));
        return false;
    } else if(!username) {
        User.noticeValidation($('#username').closest('.form-group'));
        return false;
    } else if(!isAdmin) {
        User.noticeValidation($('#isAdmin').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
User.validateUpd = function(el) {
    var uid = $("#uid").val();
    var username = $("#username").val();
    var isAdmin = $("#isAdmin").val();
    if(!uid) {
        User.noticeValidation($('#uid').closest('.form-group'));
        return false;
    } else if(!username) {
        User.noticeValidation($('#username').closest('.form-group'));
        return false;
    } else if(!isAdmin) {
        User.noticeValidation($('#isAdmin').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
User.validateDel = function(el) {
    var id = $("#uid").val();
    if(!id) {
        User.noticeValidation($('#uid').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
//添加
User.add = function(option, fn) {
	var data = {};
    var uid = data.uid = $("#uid").val();
    var username = data.username = $("#username").val();
    var isAdmin = data.isAdmin = $("#isAdmin").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
    		User.datatable.draw(true);
    	} else {
    		User.notyError(err);
    	}
    };//回调函数

    User.Server.add(data, option, fn);
};
//修改
User.upd = function(option, fn) {
	var data = {};
    var uid = data.uid = $("#uid").val();
    var username = data.username = $("#username").val();
    var isAdmin = data.isAdmin = $("#isAdmin").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
    		User.datatable.draw(true);
    	} else {
    		User.notyError(err);
    	}
    };//回调函数

    User.Server.upd(uid, data, option, fn);
};
//删除
User.del = function(option, fn) {
    var id = $('#uid').val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
        bootbox.hideAll();
        if(!err) {
            User.dtapi.ajax.reload();
        } else {
            User.notyError(err);
        }
    };//回调函数

    User.Server.del(id, option, fn);
};
// noty
User.notyError = function(err) {
	noty({
		theme: 'urban-noty',
        text: err,
        type: 'error',
        timeout: 3000,
        layout: 'topRight',
        closeWith: ['button', 'click'],
        animation: {
            open: 'in',
            close: 'out',
            easing: 'swing'
        }
	});
};
User.notyWarn = function(warn) {
	noty({
		theme: 'urban-noty',
        text: warn,
        type: 'warn',
        timeout: 3000,
        layout: 'topRight',
        closeWith: ['button', 'click'],
        animation: {
            open: 'in',
            close: 'out',
            easing: 'swing'
        }
	});
};
// UI , HTML
User.Html = {};
User.Html.operation = function() {
    var html = '<a class="fa-hover pr10 pl10 view"><i class="fa fa-eye fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 edit"><i class="fa fa-pencil fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 remove"><i class="fa fa-minus-circle fa-2"> </i></a>';
    return html;
};
User.Html.operationAdd = function() {
    var html = '<a href="javascript:;" class="fa-hover form-control-static pl15 pr15 new"><i class="fa fa-plus fa-2"></i></a>';
    return html;
};
User.Html.view = function(user) {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">用户ID</label> <div class="col-sm-10 form-control-static">'+user.uid+'</div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">用户名</label> <div class="col-sm-10 form-control-static">'+user.username+'</div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">是否管理员</label> <div class="col-sm-10 form-control-static">'+user.isAdmin+'</div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
User.Html.edit = function(user) {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">用户ID</label> <div class="col-sm-10"> <input name="uid" id="uid" class="form-control" value="'+user.uid+'" readonly="readonly"> </div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">用户名</label> <div class="col-sm-10"> <input name="username" id="username" class="form-control" value="'+user.username+'"> </div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">是否管理员</label> <div class="col-sm-10"> '+
							'<select class="form-control" id="isAdmin" name="isAdmin" size="1">' + 
								'<option value="0"' + (user.isAdmin == 0 ? ' selected="true"' : '') + '>0</option>' + 
								'<option value="1"' + (user.isAdmin == 1 ? ' selected="true"' : '') + '>1</option>' + 
								'<option value="2"' + (user.isAdmin == 2 ? ' selected="true"' : '') + '>2</option>' + 
							'</select></div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
User.Html.add = function() {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">用户ID</label> <div class="col-sm-10"> <input name="uid" id="uid" class="form-control"> </div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">用户名</label> <div class="col-sm-10"> <input name="username" id="username" class="form-control"> </div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">是否管理员</label> <div class="col-sm-10">'+
							'<select class="form-control" id="isAdmin" name="isAdmin" size="1">' + 
								'<option value="0">0</option>' + 
								'<option value="1">1</option>' + 
								'<option value="2">2</option>' + 
							'</select></div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
User.Html.del = function(user) {
    var html = '<div class="row ml15 mr15">' + 
                '<input type="hidden" id="uid" name="uid", value="' + user.uid + '"/>' + 
                '<div class="col-sm-12">确定删除“' + user.username + '”吗？</div>' + 
            '</div>';
    return html;
};

// user server
User.Server = {};
/**
 * 新增用户
 */
User.Server.add = function(data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数

	$.ajax({
		url: '/admin/ajax/user/add.json',
		data: data,
        method: 'post',
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret);
			}
		},
		error: function(e) {

		}
	});
};

/**
 * 删除用户
 */
User.Server.del = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.uid = id;

	$.ajax({
		url: '/admin/ajax/user/del.json',
		data: data,
        method: 'post',
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret);
			}
		},
		error: function(e) {

		}
	});
};

/**
 * 更新用户
 */
User.Server.upd = function(id, data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.uid = id;

	$.ajax({
		url: '/admin/ajax/user/upd.json',
		data: data,
        method: 'post',
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret);
			}
		},
		error: function(e) {

		}
	});
};

/**
 * 获取用户
 */
User.Server.get = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.uid = id;

	$.ajax({
		url: '/admin/ajax/user/get.json',
		data: data,
        method: 'post',
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret);
			}
		},
		error: function(e) {

		}
	});
};

$(document).ready(function(){
	var exists = $('.main-content.admin-user');
	if(exists.length == 0) {
		return;
	}
	User.init();
});