var Grant = Admin.Grant = Urban.Grant = {};
Grant.Html={};
Grant.server={};
Grant.grants = Array();
Grant.init = function(){
	var uid = null;
	Grant.query();
	$('.adminGrant').click(function(){
		uid = this.outerText;
		grantUser = Grant.get(uid);
		var grants = {};
		if(grantUser){
			var grantStr = grantUser.grants.split(';');
			grants.uid=uid;
			grants.grants = grantStr;
		}else{
			grants.uid=uid;
			grants.grants = [];
		}
		bootbox.dialog({
			message: Grant.Html.edit(grants),
			title:'权限管理',
            buttons: [{
                label: '取消',
                callback: Grant.fnCancel
            }, {
                label: '确定',
                callback: Grant.fnOk
            }]
        });
		$('.checkbo').checkBo();
		Grant.listen();
	});
};

Grant.listen = function(){
	$(".cb-checkbox").each(function(){
		var click = false;
		if($(this).hasClass('checked')){
			click = true;
			if(!$(this).hasClass('re_check')){
				$(this).addClass('re_check');
			}
		}
		$(this).click(function(){
			var flag = $(this).hasClass('re_check');
			if(click) {
				if(!flag){
					$(this).addClass('checked').addClass('re_check');
					$(this).nextAll('div').find('label').addClass('checked').addClass('re_check');
					$(this).parents('div').children('label:first-child').addClass('checked').addClass('re_check');
				}else{
					$(this).removeClass('checked').removeClass('re_check');
					$(this).nextAll('div').find('label').removeClass('checked').removeClass('re_check');
					//$(this).parents('.content').find('label:first-child').removeClass('checked').removeClass('re_check');							
				}	
			}else if(!click){
				if(!flag){
					$(this).addClass('checked').addClass('re_check');
					$(this).parents('div').children('label:first-child').addClass('checked').addClass('re_check');$(this).nextAll('div').find('label').addClass('checked').addClass('re_check');
				}else{
					$(this).removeClass('checked');
					$(this).removeClass('re_check');
					$(this).nextAll('div').find('label').removeClass('re_check').remove('checked');	
					//$(this).parents('.content').find('label:first-child').removeClass('checked').removeClass('re_check');	
				}
			}
			click = !click;
		});
	});
};

Grant.fnCancel = function(){
	
};

Grant.fnOk = function(){
	Grant.upd({}, function(err, ret) {
		if(!err) {
			bootbox.hideAll();
			Urban.notySuccess();
		} else {
			Urban.notyError(err);
		}
	});
	return true;
};
//编辑权限发送服务器端
Grant.upd = function(option, fn){
    var data = {};
	var grants = [];
	$('.checked input[type ="checkbox"]').each(function(){
		grants.push($(this).val());
	});
	data.grants = grants;
	data.uid = $('#uid').val();
	fn = _.isFunction(fn) ? fn : function(err, ret) {
		bootbox.hideAll();
    	if(!err) {
    		bootbox.hideAll();
			Urban.notySuccess();
    	} else {
    		Grant.notyError(err);
    	}
    };//回调函数
	Grant.server.upd(data,fn);
};
Grant.server.upd = function(data,fn){
	data = _.isObject(data) ? data : {};
	fn = _.isFunction(fn) ? fn : function() {};
	$.ajax({
		url:"/admin/ajax/grant/upd.json",
		data: data,
        method: 'post',
        timeout:90000,
		async:false,
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				fn(ret.error ? ret.error : ret.code, null);
			} else {
				fn(null, ret.data);
			}
		}
	});
}
//编辑权限页面
Grant.Html.edit = function(userGrants) {
	var html='';
	var tab='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	var grants = Grant.grants;
	html+='<div class="main-content checkbo"> <div class="panel mb5"> <div class=panel-body ui-jq=checkBo> <div class=row><div class="col-sm-12">';
	html+='<input type="hidden" id="uid" value="'+userGrants.uid+'" />'; 
	for(var i=0;i<grants.length;i++){	
		if(grants[i]!=null){
			html+='<div class="content">';
			var check = Grant.checked(grants[i].id,userGrants.grants)==true?"checked":"";
			html+='<label class="cb-checkbox cb-md '+check+'"><input type="checkbox" name="default-checkbox-name" value="'+grants[i].id+'"/>'+grants[i].name+'</label>';
			var len = Object.getOwnPropertyNames(grants[i]).length;
			html+=Grant.htmls(grants[i],userGrants.grants,check,len,1);
			html+='</div>';
		}
	}
	html+='</div></div></div></div></div>'
	return html;
};
Grant.htmls = function(grants,userGrants,check,len,count){
	var tab='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	var check1 =false;
	var html = '';
	if(len>2){					
		for(var i=0;i<len-2;i++){		
			html+='<div class="content">';
			html+=tab;
			for(var j=0;j<count;j++){
				html+=tab;
			}
			//var level = 'level'+count;
			check1 = /*check=="checked"||*/Grant.checked(grants[i].id,userGrants)==true?"checked":"";
			html+='<label class="cb-checkbox cb-sm '/*+level+' '*/+check1+'"><input type="checkbox" name="default-checkbox-name" value="'+grants[i]['id']+'"/>'+grants[i]['name']+'</label>';
			if(grants[i]!=null) {
				//html+='<br />';
				var len1 = Object.getOwnPropertyNames(grants[i]).length;
				if(len1>2){
					html+=Grant.htmls(grants[i],userGrants,check1,len1,count+1);
				}
			}
			html+='</div>';
		}
		//html+='<br />';
	}else{
		html+='<div class="content"></div>';
	}
	
	return html;
}
Grant.checked = function(uid,grants){
	var flag =false;
	for(var i=0;i<grants.length;i++){
		if(uid==grants[i]){
			flag=true;
			break;
		}
	}
	return flag;
}
Grant.get = function(uid){
	var data ={};
	data.uid = uid;
	var grantUser;
	var fn = function(err, ret) {
            if(!err) {
				grantUser = ret;
            } else {
                Urban.notyError(err);
            }
		}	
	Grant.server.get(data,fn);
	return grantUser;
}
//根据uid查询grantUser是否存在
Grant.server.get = function(data,fn){
	data = _.isObject(data) ? data : {};
	fn = _.isFunction(fn) ? fn : function() {};
	$.ajax({
		url:"/admin/ajax/grant/get.json",
		data: data,
        method: 'post',
        timeout:90000,
		async:false,
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
	})
};
Grant.query = function(){
	var fn = function(err, ret) {
            if(!err) {
				Grant.grants = ret;
            } else {
                Urban.notyError(err);
            }
		}	
	Grant.server.query(fn);
};
//查询所有普通管理员权限
Grant.server.query = function(fn){
	fn = _.isFunction(fn) ? fn : function() {};
	$.ajax({
		url:"/admin/ajax/grant/query.json",
		//data: data,
        method: 'post',
        timeout:90000,
		async:false,
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
	if(!App.Util.exists('.main-content.admin-grant')) {
		return;
	}
	Grant.init();
});
