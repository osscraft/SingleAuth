var Setting = Admin.Setting = Urban.Setting = {};

Setting.datatable = null;
Setting.dtapi = null;
Setting.dtoption = {
    language: {
        "url": "/lib/datatables/plug-ins/i18n/Chinese.json"
    }
};
Setting.init = function() {
	Setting.datatable = $('.datatable').dataTable($.extend(Setting.dtoption, {
    	sAjaxSource : '/admin/ajax/setting/query.json',
        columns: [{
        	class: "align-middle",
            "data": "k"
        }, {
        	class: "align-middle",
            "data": "v"
        }, {
        	class: "align-middle",
            "data": "info"
        }, {
        	width : "110px",
			class: "align-middle",
            "orderable": false,
            "data": null,
            "defaultContent": Setting.Html.operation()
        }],
        order: [
            [0, 'asc']
        ]
    })).one('draw.dt', function() {
		$('.toolbar').html(Setting.Html.operationAdd());
		Setting.listenOperation();
    }).on('click', 'tbody tr', function () {
    	//Setting.selectRow(this);
    });
	Setting.dtapi = Setting.datatable.api();
};
Setting.selectRow = function(row) {
    if ( $(row).hasClass('selected') ) {
        $(row).removeClass('selected');
        $('#remove').addClass('disabled');
        $('#edit').addClass('disabled');
    } else {
        Setting.datatable.$('tr.selected').removeClass('selected');
        $(row).addClass('selected');
        $('#remove').removeClass('disabled');
        $('#edit').removeClass('disabled');
    }
};
Setting.listenOperation = function() {
	Setting.datatable.on('click', 'tbody td a.edit', function (e) {
		var row = Setting.datatable.fnGetData($(this).parents('tr'));
		bootbox.dialog({
			message: Setting.Html.edit(row),
    		title:'修改配置项',
			buttons: {
				success: {
					label:"保存",
					className: "btn-success",
					callback:Setting.fnUpdOk
				},
				cancle: {
					label:"取消",
					callback:Setting.fnUpdCancel
				}
			}
		})
		Setting.optionEditForm();
    	//Setting.addRowSelection(this);
    }).on('click', 'tbody td a.remove', function () {
		var row = Setting.datatable.fnGetData($(this).parents('tr'));
		bootbox.dialog({
            message: Setting.Html.del(row),
            title:'删除配置项',
            buttons: [{
                label: '取消',
                callback: Setting.fnDelCancel
            }, {
                label: '确定',
                callback: Setting.fnDelOk
            }]
        });
    }).on('click', 'tbody td a.view', function (e) {
    	var row = Setting.datatable.fnGetData($(this).parents('tr'));
        bootbox.dialog({
    		message: Setting.Html.view(row),
    		title:'查看配置项'
    	});
    })
    $('.new').click(function() {
    	bootbox.dialog({
    		message:Setting.Html.add(),
    		locale:'zh_CN',
    		title:'新增配置项',
			buttons: {
				success: {
					label:"保存",
					className: "btn-success",
					callback:Setting.fnAddOk
				},
				cancle: {
					label:"取消",
					callback:Setting.fnAddCancel
				}
			}
    	});
		Setting.optionEditForm();
    });
};
// callbacks
Setting.fnAddCancel = function(e) {
    // nothing to do
};
Setting.fnAddOk = function(e) {
    if(Setting.validateAdd()) {
        // TODO add new client
        console.log('add');
        Setting.add({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Setting.dtapi.ajax.reload();
            } else {
                Setting.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
Setting.fnUpdCancel = function(e) {
    // nothing to do
};
Setting.fnUpdOk = function(e) {
    if(Setting.validateUpd()) {
        // TODO modify client
        Setting.upd({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Setting.dtapi.ajax.reload();
            } else {
                Setting.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
// deprecated
Setting.fnDelCancel = function(e) {
    // nothing to do
};
Setting.fnDelOk = function(result) {
    if(Setting.validateDel()) {
        // TODO modify client
        Setting.del({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Setting.dtapi.ajax.reload();
            } else {
                Setting.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
Setting.optionAddForm = function() {
	$('.form-group input, form-group select').on('focus', function() {
    	$(this).closest('.form-group').removeClass('has-error');
    });
};
Setting.optionEditForm = function() {
    Setting.optionAddForm();
};
Setting.noticeValidation = function(selector) {// scroll to and class change selector
    $('.bootbox').scrollTo(selector, {offsetTop:0}, function() {
        App.Alt.flash(selector, 1, 0.1, 'class', 'form-group has-error', {attr:true});
    });
};
Setting.validateAdd = function(el) {
    var k = $("#k").val();
    var v = $("#v").val();
    var info = $("#info").val();
    if(!k) {
        Setting.noticeValidation($('#k').closest('.form-group'));
        return false;
    //} else if(!v) {
    //    Setting.noticeValidation($('#v').closest('.form-group'));
    //    return false;
    //} else if(!info) {
    //    Setting.noticeValidation($('#info').closest('.form-group'));
    //    return false;
    } else {
        return true;
    }
};
Setting.validateUpd = function(el) {
    var k = $("#k").val();
    var v = $("#v").val();
    var info = $("#info").val();
    if(!k) {
        Setting.noticeValidation($('#k').closest('.form-group'));
        return false;
    //} else if(!v) {
    //    Setting.noticeValidation($('#v').closest('.form-group'));
    //    return false;
    //} else if(!info) {
    //    Setting.noticeValidation($('#info').closest('.form-group'));
    //    return false;
    } else {
        return true;
    }
};
Setting.validateDel = function(el) {
    var id = $("#k").val();
    if(!id) {
        Setting.noticeValidation($('#k').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
//添加
Setting.add = function(option, fn) {
	var data = {};
    var k = data.k = $("#k").val();
    var v = data.v = $("#v").val();
    var info = data.info = $("#info").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
    		Setting.datatable.draw(true);
    	} else {
    		Setting.notyError(err);
    	}
    };//回调函数

    Setting.Server.add(data, option, fn);
};
//修改
Setting.upd = function(option, fn) {
	var data = {};
    var k = $("#k").val();
    var v = data.v = $("#v").val();
    var info = data.info = $("#info").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
    		Setting.datatable.draw(true);
    	} else {
    		Setting.notyError(err);
    	}
    };//回调函数

    Setting.Server.upd(k, data, option, fn);
};
//删除
Setting.del = function(option, fn) {
    var id = $('#k').val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
        bootbox.hideAll();
        if(!err) {
            Setting.dtapi.ajax.reload();
        } else {
            Setting.notyError(err);
        }
    };//回调函数

    Setting.Server.del(id, option, fn);
};
// noty
Setting.notyError = function(err) {
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
Setting.notyWarn = function(warn) {
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
Setting.Html = {};
Setting.Html.operation = function() {
    var html = '<a class="fa-hover pr10 pl10 view"><i class="fa fa-eye fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 edit"><i class="fa fa-pencil fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 remove"><i class="fa fa-minus-circle fa-2"> </i></a>';
    return html;
};
Setting.Html.operationAdd = function() {
    var html = '<a href="javascript:;" class="fa-hover form-control-static pl15 pr15 new"><i class="fa fa-plus fa-2"></i></a>';
    return html;
};
Setting.Html.view = function(setting) {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">键</label> <div class="col-sm-10 form-control-static">'+setting.k+'</div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">值</label> <div class="col-sm-10 form-control-static">'+setting.v+'</div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">配置项说明</label> <div class="col-sm-10 form-control-static">'+setting.info+'</div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
Setting.Html.edit = function(setting) {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">键</label> <div class="col-sm-10"> <input name="k" id="k" class="form-control" value="'+setting.k+'" readonly="readonly"> </div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">值</label> <div class="col-sm-10"> <input name="v" id="v" class="form-control" value="'+setting.v+'"> </div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">配置项说明</label> <div class="col-sm-10"> <input name="info" id="info" class="form-control" value="'+setting.info+'"> </div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
Setting.Html.add = function() {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">键</label> <div class="col-sm-10"> <input name="k" id="k" class="form-control"> </div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">值</label> <div class="col-sm-10"> <input name="v" id="v" class="form-control"> </div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">配置项说明</label> <div class="col-sm-10"> <input name="info" id="info" class="form-control"> </div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
Setting.Html.del = function(setting) {
    var html = '<div class="row ml15 mr15">' + 
                '<input type="hidden" id="k" name="k", value="' + setting.k + '"/>' + 
                '<div class="col-sm-12">确定删除“' + setting.k + '”吗？</div>' + 
            '</div>';
    return html;
};

// setting server
Setting.Server = {};
/**
 * 新增配置项
 */
Setting.Server.add = function(data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数

	$.ajax({
		url: '/admin/ajax/setting/add.json',
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
 * 删除配置项
 */
Setting.Server.del = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.k = id;

	$.ajax({
		url: '/admin/ajax/setting/del.json',
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
 * 更新配置项
 */
Setting.Server.upd = function(id, data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.k = id;
	$.ajax({
		url: '/admin/ajax/setting/upd.json',
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
 * 获取配置项
 */
Setting.Server.get = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.k = id;

	$.ajax({
		url: '/admin/ajax/setting/get.json',
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
	var exists = $('.main-content.admin-setting');
	if(exists.length == 0) {
		return;
	}
	Setting.init();
});