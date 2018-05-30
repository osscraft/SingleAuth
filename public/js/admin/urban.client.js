var Client = Admin.Client = Urban.Client = {};

Client.datatable = null;
Client.dtapi = null;
Client.dtoption = {
    language: {
        "url": "/lib/datatables/plug-ins/i18n/Chinese.json"
    }
};
Client.init = function() {
    // create datatable
	Client.datatable = $('.datatable').dataTable($.extend(Client.dtoption, {
        sAjaxSource : '/admin/ajax/client/query.json',
        processing: true,
        columns: [{
            class: "align-middle",
            data: "id"
        }, {
            class: "align-middle",
            data: "clientId"
        }, {
            class: "align-middle",
            data: "clientName"
        }, {
            class: "align-middle",
            data: "clientType",
            render: function(val) {
                if(val == 'jsApp') {
                    return 'JS应用';
                } else if(val == 'webApp') {
                    return 'Web应用';
                } else if(val == 'desktopApp') {
                    return '桌面应用';
                } else if(val == 'mobileApp') {
                    return '移动应用';
                } else {
                    return '未知';
                }
            }
        }, {
            class: "align-middle",
            data: "clientIsShow",
            render: function(val) {
                if(val > 0) {
                    return '是';
                } else {
                    return '否';
                }
            }
        }, {
            class: "align-middle",
            data: "clientOrderNum"
        }, {
            width : "110px",
            class: "align-middle",
            orderable: false,
            data: null,
            defaultContent: Client.Html.operation()
        }],
        order: [
            [0, 'desc']
        ]
    })).one('draw.dt', function() {
        // add in toolbar
        $('.toolbar').html(Client.Html.operationAdd());
        // listen
    	Client.listenOperation();
    }).on('click', 'tbody tr', function () {
    	//Client.selectRow(this);
    });
    // set datatable api
    Client.dtapi = Client.datatable.api();
};
Client.selectRow = function(row) {
    if ( $(row).hasClass('selected') ) {
        $(row).removeClass('selected');
        $('#remove').addClass('disabled');
        $('#edit').addClass('disabled');
    } else {
        Client.datatable.$('tr.selected').removeClass('selected');
        $(row).addClass('selected');
        $('#remove').removeClass('disabled');
        $('#edit').removeClass('disabled');
    }
};
Client.listenOperation = function() {
    // init operation event
	Client.datatable.on('click', 'tbody td a.edit', function (e) {
        var row = Client.datatable.fnGetData($(this).parents('tr'));
    	bootbox.dialog({
    		message: Client.Html.edit(row),
    		title:'修改应用',
    		buttons: [{
			    label: '取消',
			    callback: Client.fnUpdCancel
			}, {
			    label: '确定',
			    callback: Client.fnUpdOk
			}]
    	});
    	Client.optionEditForm();
    }).on('click', 'tbody td a.remove', function (e) {
        var row = Client.datatable.fnGetData($(this).parents('tr'));
        bootbox.dialog({
            message: Client.Html.del(row),
            title:'删除应用',
            buttons: [{
                label: '取消',
                callback: Client.fnDelCancel
            }, {
                label: '确定',
                callback: Client.fnDelOk
            }]
        });
    }).on('click', 'tbody td a.view', function (e) {
    	var row = Client.datatable.fnGetData($(this).parents('tr'));
        bootbox.dialog({
    		message: Client.Html.view(row),
    		title:'查看应用'
    	});
    });
    // listen add
    $('.new').click(function() {
    	bootbox.dialog({
    		message: Client.Html.add(),
    		title:'新增应用',
    		buttons: [{
			    label: '取消',
			    callback: Client.fnAddCancel
			}, {
			    label: '确定',
			    callback: Client.fnAddOk
			}]
    	});
        // render add form option
    	Client.optionAddForm();
    });
    // listen refresh
    $('.refresh').click(function() {
        Client.dtapi.ajax.reload();
    });
};
// callbacks
Client.fnAddCancel = function(e) {
    // nothing to do
};
Client.fnAddOk = function(e) {
    if(Client.validateAdd()) {
        // TODO add new client
        Client.add({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Client.dtapi.ajax.reload();
                Urban.notySuccess();
            } else {
                Urban.notyError(err);
            }
        });
        return false;
    } else {
        return false;
    }
};
Client.fnUpdCancel = function(e) {
    // nothing to do
};
Client.fnUpdOk = function(e) {
    if(Client.validateUpd()) {
        // TODO modify client
        Client.upd({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Client.dtapi.ajax.reload();
                Urban.notySuccess();
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
Client.fnDelCancel = function(e) {
    // nothing to do
};
Client.fnDelOk = function(result) {
    if(Client.validateDel()) {
        // TODO modify client
        Client.del({}, function(err, ret) {
            if(!err) {
                bootbox.hideAll();
                Client.dtapi.ajax.reload();
                Urban.notySuccess();
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
Client.optionAddForm = function() {
	$('.form-group input, form-group select').on('focus', function() {
    	$(this).closest('.form-group').removeClass('has-error');
    });
};
Client.optionEditForm = function() {
    Client.optionAddForm();
};
// some method
Client.genClientSecret = function() {
	var t = new Date();
	return hex_md5('' + Math.random() + t.getTime());
};
Client.setClientSecret = function() {
	var secret = Client.genClientSecret();
    $("#clientSecret").val(secret);
    $("#clientSecret").closest('.form-group').removeClass('has-error');
};
// validation
Client.noticeValidation = function(selector) {// scroll to and class change selector
    $('.bootbox').scrollTo(selector, {offsetTop:0}, function() {
        App.Alt.flash(selector, 1, 0.1, 'class', 'form-group has-error', {attr:true});
    });
};
Client.validateAdd = function(el) {
    var clientId = $("#clientId").val();
    var clientName = $("#clientName").val();
    var clientSecret = $("#clientSecret").val();
    if(!clientId) {
        Client.noticeValidation($('#clientId').closest('.form-group'));
        return false;
    } else if(!clientName) {
        Client.noticeValidation($('#clientName').closest('.form-group'));
        return false;
    } else if(!clientSecret) {
        Client.noticeValidation($('#clientSecret').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
Client.validateUpd = function(el) {
    var clientId = $("#clientId").val();
    var clientName = $("#clientName").val();
    var clientSecret = $("#clientSecret").val();
    if(!clientId) {
        Client.noticeValidation($('#clientId').closest('.form-group'));
        return false;
    } else if(!clientName) {
        Client.noticeValidation($('#clientName').closest('.form-group'));
        return false;
    } else if(!clientSecret) {
        Client.noticeValidation($('#clientSecret').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
Client.validateDel = function(el) {
    var id = $("#id").val();
    if(!id) {
        Client.noticeValidation($('#id').closest('.form-group'));
        return false;
    } else {
        return true;
    }
};
//添加
Client.add = function(option, fn) {
	var data = {};
    var clientId = data.clientId = $("#clientId").val();
    var clientName = data.clientName = $("#clientName").val();
    var clientSecret = data.clientSecret = $("#clientSecret").val();
    var clientType = data.clientType = $("#clientType").val();
    var redirectURI = data.redirectURI = $("#redirectURI").val();
    var clientDescribe = data.clientDescribe = $("#clientDescribe").val();
    var clientScope = data.clientScope = $("#clientScope").val();
    var clientLocation = data.clientLocation = $("#clientLocation").val();
    var clientLogoUri = data.clientLogoUri = $("#clientLogoUri").val();
    var clientIsShow = data.clientIsShow = $("#clientIsShow").val();
    var clientVisible = data.clientVisible = $("#clientVisible").val();
    var clientOrderNum = data.clientOrderNum = $("#clientOrderNum").val();
    var tokenLifetime = data.tokenLifetime = $("#tokenLifetime").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
            Client.dtapi.ajax.reload();
    	} else {
    		Urban.notyError(err);
    	}
    };//回调函数

    Client.Server.add(data, option, fn);
};
//修改
Client.upd = function(option, fn) {
	var data = {};
	var id = data.id = $('#id').val();
    var clientId = data.clientId = $("#clientId").val();
    var clientName = data.clientName = $("#clientName").val();
    var clientSecret = data.clientSecret = $("#clientSecret").val();
    var clientType = data.clientType = $("#clientType").val();
    var redirectURI = data.redirectURI = $("#redirectURI").val();
    var clientDescribe = data.clientDescribe = $("#clientDescribe").val();
    var clientScope = data.clientScope = $("#clientScope").val();
    var clientLocation = data.clientLocation = $("#clientLocation").val();
    var clientLogoUri = data.clientLogoUri = $("#clientLogoUri").val();
    var clientIsShow = data.clientIsShow = $("#clientIsShow").val();
    var clientVisible = data.clientVisible = $("#clientVisible").val();
    var clientOrderNum = data.clientOrderNum = $("#clientOrderNum").val();
    var tokenLifetime = data.tokenLifetime = $("#tokenLifetime").val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
            Client.dtapi.ajax.reload();
    	} else {
    		Urban.notyError(err);
    	}
    };//回调函数

    Client.Server.upd(id, data, option, fn);
};
//删除
Client.del = function(option, fn) {
    var id = $('#id').val();
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function(err, ret) {
        bootbox.hideAll();
        if(!err) {
            Client.dtapi.ajax.reload();
        } else {
            Urban.notyError(err);
        }
    };//回调函数

    Client.Server.del(id, option, fn);
};
// UI , HTML
Client.Html = {};
Client.Html.operation = function() {
    var html = '<a class="fa-hover pr10 pl10 view"><i class="fa fa-eye fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 edit"><i class="fa fa-pencil fa-2"> </i></a>' + 
            '<a class="fa-hover pr10 pl10 remove"><i class="fa fa-minus-circle fa-2"> </i></a>';
    return html;
};
Client.Html.operationAdd = function() {
    var html = '<a href="javascript:;" class="fa-hover form-control-static ml15 pl15 pr15 new">' + 
                '<i class="fa fa-plus fa-2"></i>' + 
            '</a>' + 
            '<a href="javascript:;" class="fa-hover form-control-static pl15 pr15 refresh">' + 
                '<i class="fa fa-refresh fa-2"></i>' + 
            '</a>';
    return html;
};
Client.Html.view = function(client) {
	var html = '<div class="row ml15 mr15">' +
				'<form class="form-horizontal">' + // bordered-group
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">ID</label>' + 
                        '<div class="col-sm-10 form-control-static">' + client.id + '</div>' + 
                    '</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">标识符</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.clientId + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">密钥</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.clientSecret + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">名称</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.clientName + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">类型</label>' + 
						'<div class="col-sm-10 form-control-static">' + 
							(client.clientType == 'webApp' ?
							'WEB应用' :
							(client.clientType == 'jsApp' ?
							'JS应用' :
                            (client.clientType == 'mobileApp' ?
                            '移动应用' : 
                            '桌面应用'))) + 
						'</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">描述</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.clientDescribe + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">重定向地址</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.redirectURI + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">资源访问域</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.clientScope + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">应用地址</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.clientLocation + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">LOGO地址</label>' + 
						'<div class="col-sm-10 form-control-static">' + client.clientLogoUri + '</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">首页显示</label>' + 
						'<div class="col-sm-10 form-control-static">' + 
							(client.clientIsShow == '1' ?
							'是' :
							'否') + 
						'</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">可见性</label>' + 
						'<div class="col-sm-10 form-control-static">' + 
							(client.clientVisible == '3' ?
							'其他' :
							(client.clientType == '2' ?
							'学生' : 
							(client.clientType == '1' ?
							'教师' : 
							'全部'))) + 
						'</div>' + 
					'</div>' +
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">排序值</label>' + 
                        '<div class="col-sm-10 form-control-static">' + client.clientOrderNum + '</div>' + 
                    '</div>' +
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">令牌时长</label>' + 
                        '<div class="col-sm-10 form-control-static">' + client.tokenLifetime + '</div>' + 
                    '</div>' +
			 	'</form>' +
			'</div>';
	return html;
};
Client.Html.edit = function(client) {
	var html = '<div class="row ml15 mr15">' +
				'<form class="form-horizontal">' + // bordered-group
                    /*'<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">ID</label>' + 
                        '<div class="col-sm-10 form-control-static"> ' + client.id + '</div>' + 
                    '</div>' +*/
                    '<input type="hidden" id="id" name="id" value="' + client.id + '">' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">标识符</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientId" name="clientId" value="' + client.clientId + '" maxlength="32" placeholder="32字符以内" readonly="readonly" disabled> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">密钥</label>' + 
						'<div class="col-sm-8"> <input class="form-control" id="clientSecret" name="clientSecret" value="' + client.clientSecret + '" readonly="readonly" disabled> </div>' + 
						'<div class="col-sm-2"> <a class="btn btn-default gen pull-right" id="clientSecretGen" onclick="Client.setClientSecret();">生成</a> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">名称</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientName" name="clientName" value="' + client.clientName + '" maxlength="50" placeholder="50字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">类型</label>' + 
						'<div class="col-sm-10">' + 
							'<select class="form-control" id="clientType" name="clientType" size="1">' + 
								'<option value="webApp"' + (client.clientType == 'webApp' ? ' selected="true"' : '') + '>WEB应用</option>' + 
                                '<option value="mobileApp"' + (client.clientType == 'mobileApp' ? ' selected="true"' : '') + '>移动应用</option>' + 
								'<option value="jsApp"' + (client.clientType == 'jsApp' ? ' selected="true"' : '') + '>JS应用</option>' + 
								'<option value="desktopApp"' + (client.clientType == 'desktopApp' ? ' selected="true"' : '') + '>桌面应用</option>' + 
							'</select>' +
						'</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">描述</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientDescribe" name="clientDescribe" value="' + client.clientDescribe + '" maxlength="1000" placeholder="1000字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">重定向地址</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="redirectURI" name="redirectURI" value="' + client.redirectURI + '" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">资源访问域</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientScope" name="clientScope" value="' + client.clientScope + '" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">应用地址</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientLocation" name="clientLocation" value="' + client.clientLocation + '" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">LOGO地址</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientLogoUri" name="clientLogoUri" value="' + client.clientLogoUri + '" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">首页显示</label>' + 
						'<div class="col-sm-10">' + 
							'<select class="form-control" id="clientIsShow" name="clientIsShow" size="1">' + 
								'<option value="0"' + (client.clientIsShow == 0 ? ' selected="true"' : '') + '>否</option>' + 
								'<option value="1"' + (client.clientIsShow == 1 ? ' selected="true"' : '') + '>是</option>' + 
							'</select>' +
						'</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">可见性</label>' + 
						'<div class="col-sm-10">' + 
							'<select class="form-control" id="clientVisible" name="clientVisible" size="1">' + 
								'<option value="0"' + (client.clientVisible == 0 ? ' selected="true"' : '') + '>全部</option>' + 
								'<option value="1"' + (client.clientVisible == 1 ? ' selected="true"' : '') + '>教师</option>' + 
								'<option value="2"' + (client.clientVisible == 2 ? ' selected="true"' : '') + '>学生</option>' + 
								'<option value="3"' + (client.clientVisible == 3 ? ' selected="true"' : '') + '>其他</option>' + 
							'</select>' +
						'</div>' + 
					'</div>' +
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">排序值</label>' + 
                        '<div class="col-sm-10"> <input class="form-control" id="clientOrderNum" name="clientOrderNum" value="' + client.clientOrderNum + '" maxlength="10" placeholder="10位数以下的数值"> </div>' + 
                    '</div>' +
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">令牌时长</label>' + 
                        '<div class="col-sm-10"> <input class="form-control" id="tokenLifetime" name="tokenLifetime" value="' + client.tokenLifetime + '" maxlength="10" placeholder="10位数以下的数值，0为原始值"> </div>' + 
                    '</div>' +
			 	'</form>' +
			'</div>';
	return html;
};
Client.Html.add = function() {
	var html = '<div class="row ml15 mr15">' +
				'<form class="form-horizontal">' + // bordered-group
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">标识符</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientId" name="clientId" maxlength="32" placeholder="32字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">密钥</label>' + 
						'<div class="col-sm-8"> <input class="form-control" id="clientSecret" name="clientSecret" value="" readonly="readonly" disabled> </div>' + 
						'<div class="col-sm-2"> <a class="btn btn-default gen pull-right" id="clientSecretGen" onclick="Client.setClientSecret();">生成</a> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">名称</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientName" name="clientName" maxlength="50" placeholder="50字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">类型</label>' + 
						'<div class="col-sm-10">' + 
							'<select class="form-control" id="clientType" name="clientType" size="1">' + 
								'<option value="webApp" selected="true">WEB应用</option>' + 
                                '<option value="mobileApp">移动应用</option>' + 
								'<option value="jsApp">JS应用</option>' + 
								'<option value="desktopApp">桌面应用</option>' + 
							'</select>' +
						'</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">描述</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientDescribe" name="clientDescribe" maxlength="1000" placeholder="1000字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">重定向地址</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="redirectURI" name="redirectURI" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">资源访问域</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientScope" name="clientScope" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">应用地址</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientLocation" name="clientLocation" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">LOGO地址</label>' + 
						'<div class="col-sm-10"> <input class="form-control" id="clientLogoUri" name="clientLogoUri" maxlength="255" placeholder="255字符以内"> </div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">首页显示</label>' + 
						'<div class="col-sm-10">' + 
							'<select class="form-control" id="clientIsShow" name="clientIsShow" size="1">' + 
								'<option value="" selected="true">否</option>' + 
								'<option value="1">是</option>' + 
							'</select>' +
						'</div>' + 
					'</div>' +
					'<div class="form-group">' + 
						'<label class="col-sm-2 control-label">可见性</label>' + 
						'<div class="col-sm-10">' + 
							'<select class="form-control" id="clientVisible" name="clientVisible" size="1">' + 
								'<option value="0" selected="true">全部</option>' + 
								'<option value="1">教师</option>' + 
								'<option value="2">学生</option>' + 
								'<option value="3">其他</option>' + 
							'</select>' +
						'</div>' + 
					'</div>' +
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">排序值</label>' + 
                        '<div class="col-sm-10"> <input class="form-control" id="clientOrderNum" name="clientOrderNum" maxlength="10" placeholder="10位数以下的数值"> </div>' + 
                    '</div>' +
                    '<div class="form-group">' + 
                        '<label class="col-sm-2 control-label">令牌时长</label>' + 
                        '<div class="col-sm-10"> <input class="form-control" id="tokenLifetime" name="tokenLifetime" maxlength="10" placeholder="10位数以下的数值，0为原始值"> </div>' + 
                    '</div>' +
			 	'</form>' +
			'</div>';
	return html;
};
Client.Html.del = function(client) {
    var html = '<div class="row ml15 mr15">' + 
                '<input type="hidden" id="id" name="id", value="' + client.id + '"/>' + 
                '<div class="col-sm-12">确定删除“' + client.clientName + '”吗？</div>' + 
            '</div>';
    return html;
};

// client server
Client.Server = {};
/**
 * 新增应用
 */
Client.Server.add = function(data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数

	$.ajax({
		url: '/admin/ajax/client/add.json',
		data: data,
        method: 'post',
        timeout:90000,
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret.data);
			}
		},
		error: function(e) {

		}
	});
};
/**
 * 删除应用
 */
Client.Server.del = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数
	data.id = id;

	$.ajax({
		url: '/admin/ajax/client/del.json',
		data: data,
        method: 'post',
        timeout:90000,
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret.data);
			}
		},
		error: function(e) {

		}
	});
};
/**
 * 更新应用
 */
Client.Server.upd = function(id, data, option, fn) {
    data = _.isObject(data) ? data : {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数
	data.id = id;

	$.ajax({
		url: '/admin/ajax/client/upd.json',
		data: data,
        method: 'post',
        timeout:90000,
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret.data);
			}
		},
		error: function(e) {

		}
	});
};
/**
 * 获取应用
 */
Client.Server.get = function(id, option, fn) {
	var data = {};
    option = _.isUndefined(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数
	data.id = id;

	$.ajax({
		url: '/admin/ajax/client/get.json',
		data: data,
        method: 'post',
        timeout:90000,
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret.data);
			}
		},
		error: function(e) {

		}
	});
};

$(document).ready(function(){
	if(!App.Util.exists('.main-content.admin-client')) {
		return;
	}

	Client.init();
});