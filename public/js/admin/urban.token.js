var Token = Admin.Token = Urban.Token = {};

Token.datatable = null;
Token.dtapi = null;
Token.dtoption = {
    language: {
        "url": "/lib/datatables/plug-ins/i18n/Chinese.json"
    }
};
Token.init = function() {
	Token.datatable = $('.datatable').dataTable($.extend(Token.dtoption, {
        sAjaxSource : '/admin/ajax/token/query.json',
        processing: true,
        columns: [{
            class: "align-middle",
            data: "oauthToken"
        }, {
            class: "align-middle",
            data: "clientId"
        }, {
            class: "align-middle",
            data: "username"
        }, {
            class: "align-middle",
            data: "expires"
        }, {
            width : "110px",
            class: "align-middle",
            orderable: false,
            data: null,
            defaultContent: Token.Html.operation()
        }],
        order: [
            [3, 'desc']
        ]
    })).one('draw.dt', function() {
    	// add toolbar
        $('.toolbar').html(Token.Html.operationAdd());
        // listen
    	Token.listenOperation();
    }).on('click', 'tbody tr', function () {
    	//Token.selectRow(this);
    });
    // set datatable api
    Token.dtapi = Token.datatable.api();
};
Token.listenOperation = function() {
    // init operation event
	Token.datatable.on('click', 'tbody td a.remove', function (e) {
        var row = Token.datatable.fnGetData($(this).parents('tr'));
        bootbox.dialog({
            message: Token.Html.del(row),
            title:'删除令牌',
            buttons: [{
                label: '取消',
                callback: Token.fnDelCancel
            }, {
                label: '确定',
                callback: Token.fnDelOk
            }]
        });
    }).on('click', 'tbody td a.view', function (e) {
    	var row = Token.datatable.fnGetData($(this).parents('tr'));
        bootbox.dialog({
    		message: Token.Html.view(row),
    		title:'查看令牌'
    	});
    });
    // listen refresh
    $('.refresh').click(function() {
        Token.dtapi.ajax.reload();
    });
};
// callbacks
Token.fnAddCancel = function(e) {
    // nothing to do
};
Token.fnAddOk = function(e) {
    if(Token.validateAdd()) {
        // TODO add new client
        Token.add({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Token.dtapi.ajax.reload();
            } else {
                Urban.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
Token.fnUpdCancel = function(e) {
    // nothing to do
};
Token.fnUpdOk = function(e) {
    if(Token.validateUpd()) {
        // TODO modify client
        Token.upd({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Token.dtapi.ajax.reload();
            } else {
                Urban.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
// deprecated
Token.fnDelCancel = function(e) {
    // nothing to do
};
Token.fnDelOk = function(result) {
    if(Token.validateDel()) {
        // TODO modify client
        Token.del({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Token.dtapi.ajax.reload();
            } else {
                Urban.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
// form option
Token.optionAddForm = function() {
	$('.form-group input, form-group select').on('focus', function() {
    	$(this).closest('.form-group').removeClass('has-error');
    });
};
Token.optionEditForm = function() {
    Token.optionAddForm();
};
// validation
Token.noticeValidation = function(selector) {// scroll to and class change selector
    $('.bootbox').scrollTo(selector, {offsetTop:0}, function() {
        App.Alt.flash(selector, 1, 0.1, 'class', 'form-group has-error', {attr:true});
    });
};
Token.validateAdd = function(el) {
    return true;
};
Token.validateUpd = function(el) {
    return true;
};
Token.validateDel = function(el) {
    return true;
};
//
Token.add = function() {

};
Token.del = function(option, fn) {
    var oauthToken = $('#oauthToken').val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
        bootbox.hideAll();
        if(!err) {
            Token.dtapi.ajax.reload();
        } else {
            Urban.notyError(err);
        }
    };//回调函数

    Token.Server.del(oauthToken, option, fn);
};
Token.upd = function() {
	
};
Token.get = function() {
	
};
//
Token.Html = {};
Token.Html.operation = function() {
    var html = '<a class="fa-hover pr10 pl10 view"><i class="fa fa-eye fa-2"> </i></a>' + 
            //'<a class="fa-hover pr10 pl10 edit"><i class="fa fa-pencil fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 remove"><i class="fa fa-minus-circle fa-2"> </i></a>';
    return html;
};
Token.Html.operationAdd = function() {
    var html = '<a href="javascript:;" class="fa-hover form-control-static ml15 pl15 pr15 refresh">' + 
                '<i class="fa fa-refresh fa-2"></i>' + 
            '</a>';
    return html;
};
Token.Html.add = function(token) {

};
Token.Html.del = function(token) {
    var html = '<div class="row ml15 mr15">' + 
                '<input type="hidden" id="oauthToken" name="oauthToken", value="' + token.oauthToken + '"/>' + 
                '<div class="col-sm-12">确定删除“' + token.oauthToken + '”吗？</div>' + 
            '</div>';
    return html;
};
Token.Html.edit = function(token) {
	
};
Token.Html.view = function(token) {
	var html = '<div class="row ml15 mr15">' +
				'<form class="form-horizontal">' + // bordered-group
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">令牌码</label>' + 
                        '<div class="col-sm-10 form-control-static">' + token.oauthToken + '</div>' + 
                    '</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">应用</label>' + 
						'<div class="col-sm-10 form-control-static">' + token.clientId + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">用户</label>' + 
						'<div class="col-sm-10 form-control-static">' + token.username + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">访问域</label>' + 
						'<div class="col-sm-10 form-control-static">' + token.scope + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">类型</label>' + 
						'<div class="col-sm-10 form-control-static">' + 
							(token.type == 0 ?
							'访问令牌' :
							'刷新令牌') + 
						'</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">过期时间</label>' + 
						'<div class="col-sm-10 form-control-static">' + token.expires + '</div>' + 
					'</div>' +
			 	'</form>' +
			'</div>';
	return html;
};
//
Token.Server = {};
Token.Server.add = function(data, option, fn) {

};
Token.Server.del = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数
	data.id = id;

	$.ajax({
		url: '/admin/ajax/token/del.json',
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
Token.Server.upd = function(id, data, option, fn) {

};
Token.Server.get = function(id, option, fn) {

};

$(document).ready(function(){
	if(!App.Util.exists('.main-content.admin-token')) {
		return;
	}

	Token.init();
});