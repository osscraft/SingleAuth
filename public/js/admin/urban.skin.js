var Skin = Admin.Skin = Urban.Skin = {};
Skin.themes = Array();

Skin.datatable = null;
Skin.dtapi = null;
Skin.dtoption = {
    language: {
        "url": "/lib/datatables/plug-ins/i18n/Chinese.json"
    }
};
Skin.init = function() {
    $('.checkbo').checkBo();

    Skin.listen();

    return;
	Skin.datatable = $('.datatable').dataTable($.extend(Skin.dtoption, {
    	sAjaxSource : '/admin/ajax/skin/query.json',
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
            "defaultContent": Skin.Html.operation()
        }],
        order: [
            [0, 'asc']
        ]
    })).one('draw.dt', function() {
		//$('.toolbar').html(Skin.Html.operationAdd());
		Skin.listenOperation();
    }).on('click', 'tbody tr', function () {
    	//Skin.selectRow(this);
    });
	Skin.dtapi = Skin.datatable.api();
};
Skin.listen = function() {
    $('.admin-skin .btn').on('click', function() {
        Skin.detectClick(this);
    });
};
Skin.detectClick = function(el) {
    var option = {};
    var p = $(el).parent();
    var v = $('.cb-radio.checked input[type="radio"]', p).val();
    option.el = el;
    if($(el).hasClass('skin-main')) {
        Skin.setThemeMain(v, option);
    } else if($(el).hasClass('skin-admin')) {
        Skin.setThemeAdmin(v, option);
    }
};
Skin.setThemeMain = function(v, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    
    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    option.key = 'main';
    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Skin.Server.set(v, option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            Urban.notySuccess('首页皮肤设置成功!');
        }
    });
};
Skin.setThemeAdmin = function(v, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    
    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    option.key = 'admin';
    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Skin.Server.set(v, option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            Urban.notySuccess('管理皮肤设置成功!');
        }
    });
};
Skin.selectRow = function(row) {
    if ( $(row).hasClass('selected') ) {
        $(row).removeClass('selected');
        $('#remove').addClass('disabled');
        $('#edit').addClass('disabled');
    } else {
        Skin.datatable.$('tr.selected').removeClass('selected');
        $(row).addClass('selected');
        $('#remove').removeClass('disabled');
        $('#edit').removeClass('disabled');
    }
};
Skin.listenOperation = function() {
	Skin.datatable.on('click', 'tbody td a.edit', function (e) {
		var row = Skin.datatable.fnGetData($(this).parents('tr'));
		bootbox.dialog({
			message: Skin.Html.edit(row),
    		title:'修改皮肤配置',
			buttons: {
				success: {
					label:"保存",
					className: "btn-success",
					callback:Skin.fnUpdOk
				},
				cancle: {
					label:"取消",
					callback:Skin.fnUpdCancel
				}
			}
		})
		Skin.optionEditForm();
    	//Skin.addRowSelection(this);
    }).on('click', 'tbody td a.remove', function () {
		var row = Skin.datatable.fnGetData($(this).parents('tr'));
		bootbox.dialog({
            message: Skin.Html.del(row),
            title:'删除皮肤配置',
            buttons: [{
                label: '取消',
                callback: Skin.fnDelCancel
            }, {
                label: '确定',
                callback: Skin.fnDelOk
            }]
        });
    }).on('click', 'tbody td a.view', function (e) {
    	var row = Skin.datatable.fnGetData($(this).parents('tr'));
        bootbox.dialog({
    		message: Skin.Html.view(row),
    		title:'查看皮肤配置'
    	});
    })
    $('.new').click(function() {
    	bootbox.dialog({
    		message:Skin.Html.add(),
    		locale:'zh_CN',
    		title:'新增皮肤配置',
			buttons: {
				success: {
					label:"保存",
					className: "btn-success",
					callback:Skin.fnAddOk
				},
				cancle: {
					label:"取消",
					callback:Skin.fnAddCancel
				}
			}
    	});
		Skin.optionEditForm();
    });
};
// callbacks
Skin.fnAddCancel = function(e) {
    // nothing to do
};
Skin.fnAddOk = function(e) {
    if(Skin.validateAdd()) {
        // TODO add new client
        console.log('add');
        Skin.add({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Skin.dtapi.ajax.reload();
            } else {
                Skin.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
Skin.fnUpdCancel = function(e) {
    // nothing to do
};
Skin.fnUpdOk = function(e) {
    if(Skin.validateUpd()) {
        // TODO modify client
        Skin.upd({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Skin.dtapi.ajax.reload();
            } else {
                Skin.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
// deprecated
Skin.fnDelCancel = function(e) {
    // nothing to do
};
Skin.fnDelOk = function(result) {
    if(Skin.validateDel()) {
        // TODO modify client
        Skin.del({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Skin.dtapi.ajax.reload();
            } else {
                Skin.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
Skin.optionAddForm = function() {
	$('.form-group input, form-group select').on('focus', function() {
    	$(this).closest('.form-group').removeClass('has-error');
    });
};
Skin.optionEditForm = function() {
    Skin.optionAddForm();
};
Skin.noticeValidation = function(selector) {// scroll to and class change selector
    $('.bootbox').scrollTo(selector, {offsetTop:0}, function() {
        App.Alt.flash(selector, 1, 0.1, 'class', 'form-group has-error', {attr:true});
    });
};
Skin.validateAdd = function(el) {
    var k = $("#k").val();
    var v = $("#v").val();
    var info = $("#info").val();
    if(!k) {
        Skin.noticeValidation($('#k').closest('.form-group'));
        return false;
    } else if(!v) {
        Skin.noticeValidation($('#v').closest('.form-group'));
        return false;
    } else if(!info) {
        Skin.noticeValidation($('#info').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
Skin.validateUpd = function(el) {
    var k = $("#k").val();
    var v = $("#v").val();
    var info = $("#info").val();
    if(!k) {
        Skin.noticeValidation($('#k').closest('.form-group'));
        return false;
    } else if(!v) {
        Skin.noticeValidation($('#v').closest('.form-group'));
        return false;
    } else if(!info) {
        Skin.noticeValidation($('#info').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
Skin.validateDel = function(el) {
    var id = $("#k").val();
    if(!id) {
        Skin.noticeValidation($('#k').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
//添加
Skin.add = function(option, fn) {
	var data = {};
    var k = data.k = $("#k").val();
    var v = data.v = $("#v").val();
    var info = data.info = $("#info").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
    		Skin.datatable.draw(true);
    	} else {
    		Skin.notyError(err);
    	}
    };//回调函数

    Skin.Server.add(data, option, fn);
};
//修改
Skin.upd = function(option, fn) {
	var data = {};
    var k = $("#k").val();
    var v = data.v = $("#v").val();
    var info = data.info = $("#info").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
    		Skin.datatable.draw(true);
    	} else {
    		Skin.notyError(err);
    	}
    };//回调函数

    Skin.Server.upd(k, data, option, fn);
};
//删除
Skin.del = function(option, fn) {
    var id = $('#k').val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
        bootbox.hideAll();
        if(!err) {
            Skin.dtapi.ajax.reload();
        } else {
            Skin.notyError(err);
        }
    };//回调函数

    Skin.Server.del(id, option, fn);
};
// UI , HTML
Skin.Html = {};
Skin.Html.operation = function() {
    var html = '<a class="fa-hover pr10 pl10 view"><i class="fa fa-eye fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 edit"><i class="fa fa-pencil fa-2"> </i></a>' ;
            //'<a class="fa-hover pr10 pl10 remove"><i class="fa fa-minus-circle fa-2"> </i></a>';
    return html;
};
Skin.Html.operationAdd = function() {
    var html = '<a href="javascript:;" class="fa-hover form-control-static pl15 pr15 new"><i class="fa fa-plus fa-2"></i></a>';
    return html;
};
Skin.Html.view = function(skin) {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">键</label> <div class="col-sm-10 form-control-static">'+skin.k+'</div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">值</label> <div class="col-sm-10 form-control-static">'+skin.v+'</div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">配置项说明</label> <div class="col-sm-10 form-control-static">'+skin.info+'</div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
Skin.Html.edit = function(skin) {
	Skin.Server.gets(skin.k);
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">键</label> <div class="col-sm-10"> <input name="k" id="k" class="form-control" value="'+skin.k+'" readonly="readonly"> </div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">值</label> <div class="col-sm-10"><select class="form-control" name="v" id="v" size="1">';
	var opts = '';
	for(var i=0;i<Skin.themes.length;i++){
		if(skin.v==Skin.themes[i]){
			opts+= '<option value="'+Skin.themes[i]+'" selected="selected">'+Skin.themes[i]+'</option>';
		}else{
			opts+= '<option value="'+Skin.themes[i]+'">'+Skin.themes[i]+'</option>';
		}
	}
	html= html+opts + '</select></div> </div>' +
			'<div class="form-group"> <label class="col-sm-2 control-label">配置项说明</label> <div class="col-sm-10"> <input name="info" id="info" class="form-control" value="'+skin.info+'"> </div> </div>' +
			'</form>' +
		'</div>';
	console.log(opts);
	return html;
};
Skin.Html.add = function() {
	var html = '<div class="row ml15 mr15">' +
    				'<form class="form-horizontal bordered-group">' + 
    					'<div class="form-group"> <label class="col-sm-2 control-label">键</label> <div class="col-sm-10"> <input name="k" id="k" class="form-control"> </div> </div>' +
    					'<div class="form-group"> <label class="col-sm-2 control-label">值</label> <div class="col-sm-10"> <input name="v" id="v" class="form-control"> </div> </div>' +
						'<div class="form-group"> <label class="col-sm-2 control-label">配置项说明</label> <div class="col-sm-10"> <input name="info" id="info" class="form-control"> </div> </div>' +
				 	'</form>' +
				'</div>';
	return html;
};
Skin.Html.del = function(skin) {
    var html = '<div class="row ml15 mr15">' + 
                '<input type="hidden" id="k" name="k", value="' + skin.k + '"/>' + 
                '<div class="col-sm-12">确定删除“' + skin.k + '”吗？</div>' + 
            '</div>';
    return html;
};

// skin server
Skin.Server = {};
Skin.Server.setURL = '/admin/ajax/skin/set.json';
/**
 * 设置皮肤
 */
Skin.Server.set = function(v, option, fn) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数

    var data = {};
    var key = option.key ? option.key : '';

    data.key = key ? key : '';
    data.v = v ? v : '';
    $.ajax({
        url: Skin.Server.setURL,
        type:'post',
        data: data,
        timeout:90000,
        dataType: 'json',
        beforeSend: option.beforeSend,
        success:function(ret){
            if(ret.code) {
                fn(App.Util.detectError(ret), null);
            } else {
                fn(null, ret.data);
            }
        },
        complete: option.complete,
        error: option.error
    });
};
/**
 * 新增配置项
 */
Skin.Server.add = function(data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数

	$.ajax({
		url: '/admin/ajax/skin/add.json',
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
Skin.Server.del = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.k = id;

	$.ajax({
		url: '/admin/ajax/skin/del.json',
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
Skin.Server.upd = function(id, data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.k = id;
	$.ajax({
		url: '/admin/ajax/skin/upd.json',
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
Skin.Server.get = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
	data.k = id;

	$.ajax({
		url: '/admin/ajax/skin/get.json',
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
Skin.Server.gets = function(id) {
	var data = {};
	data.k=id;
	var themes ={};
	$.ajax({
		url: '/admin/ajax/skin/gets.json',
        method: 'post',
		data:data,
		async:false,
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				
			} else {
				Skin.themes=ret.data.themes;
			}
		}
	});
};
$(document).ready(function(){
	var exists = $('.main-content.admin-skin');
	if(exists.length == 0) {
		return;
	}
	Skin.init();
});