var Urban = Admin.Urban = {};
var UrbanApp = Admin.UrbanApp = {};

UrbanApp.name = 'Urban';
UrbanApp.year = (new Date).getFullYear();
UrbanApp.font = 'RobotoDraft';
UrbanApp.default = '#e2e2e2';
UrbanApp.textColor = '#6B6B6B';
UrbanApp.white = 'white';
UrbanApp.border = '#e4e4e4';
UrbanApp.dark = '#4C5064';
UrbanApp.danger = '#d96557';
UrbanApp.info = '#4cc3d9';
UrbanApp.success = '#2ECC71';
UrbanApp.warning = '#ffc65d';
UrbanApp.bodyBg = '#e0e8f2';
UrbanApp.primary = '#09c';
UrbanApp.dblue = 'rgb(57,137,209)';

Urban.init = function() {
	if(App.Util.exists('.sidebar-panel')) {
		Urban.initSidebarStatus();
	}
	// perfect-scrollbar
	$('.ps-container').perfectScrollbar();
	//listen
	Urban.listen();
};
Urban.initSidebarStatus = function() {
	// sidebar cookie
	var data_toggle = $.cookie('sso-admin-sidebar');
	if(data_toggle) {
		var has = $('.app').hasClass(data_toggle);
		if(!has) {
			$('.app').addClass(data_toggle);
		}
	}
};
Urban.updateSidebarStatus = function(data_toggle) {
	$.cookie('sso-admin-sidebar', data_toggle, {path:'/'});
};
Urban.removeSidebarStatus = function() {
	$.cookie('sso-admin-sidebar', '', {path:'/'});
};
Urban.getSidebarStatus = function() {
	var status = $.cookie('sso-admin-sidebar');
	return status ? status : '';
};
//listen
Urban.listen = function() {
	// sidebar toggle
	$('.app .sidebar-panel .toggle-sidebar,.app .toggle-offscreen a').on('click', function(e) {
		var data_toggle = $(this).attr('data-toggle');
		var has = $('.app').hasClass(data_toggle);
		if(has) {
			$('.app').removeClass(data_toggle);
			Urban.removeSidebarStatus();
		} else {
			$('.app').addClass(data_toggle);
			Urban.updateSidebarStatus(data_toggle);
		}
	});
	// right chatbar toggle
	$('.app .navbar .toggle-chatbar').on('click', function(e) {
		var data_toggle = $(this).attr('data-toggle');
		var has = $('.app').hasClass(data_toggle);
		if(has) {
			$('.app').removeClass(data_toggle);
		} else {
			$('.app').addClass(data_toggle);
		}
	});
	// right dropdown
	$('.app .navbar .toggle-dropdown').on('click', function(e) {
		var closest = $(this).closest('li');
		var has = closest.hasClass('open');
		$('.app .navbar .toggle-dropdown').parents('li').removeClass('open');
		if(has) {
			closest.removeClass('open');
		} else {
			closest.addClass('open');
		}
	});
	// nav toggle
	$('.app .nav .menu-accordion a').on('click', function() {
		var href = $(this).attr('href');
		var status = Urban.getSidebarStatus();
		if(href && href != 'javascript:;' && href != 'javascript:void(0);') {
			// 小屏时点击关闭左菜单栏
			if(status && status == 'offscreen move-left') {
				Urban.removeSidebarStatus();
			}
			return;
		}
		var el = $(this).closest('.menu-accordion');
		var open = el.hasClass('open');
		$('.app .nav .menu-accordion.open').removeClass('open');
		$(el).parents('.menu-accordion').addClass('open');
		if(open) {
			$(el).removeClass('open');
		} else {
			$(el).addClass('open');
		}
	});
	$('.app .scroll-up').on('click', function() {
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	// scroll top
};
// noty
Urban.notySuccess = function(msg) {
    msg = _.isUndefined(msg) ? '操作成功' : msg;//更多选项

	noty({
		theme: 'urban-noty',
        text: msg,
        type: 'success',
        timeout: 3000,
        layout: 'top',
        closeWith: ['button', 'click'],
        animation: {
            open: 'in',
            close: 'out',
            easing: 'swing'
        }
	});
};
Urban.notyError = function(err) {
    err = _.isUndefined(err) ? '操作失败' : err;//更多选项

	noty({
		theme: 'urban-noty',
        text: err,
        type: 'error',
        timeout: 3000,
        layout: 'top',
        closeWith: ['button', 'click'],
        animation: {
            open: 'in',
            close: 'out',
            easing: 'swing'
        }
	});
};
Urban.notyWarn = function(warn) {
    warn = _.isUndefined(warn) ? '操作失败' : warn;//更多选项
    
	noty({
		theme: 'urban-noty',
        text: warn,
        type: 'warn',
        timeout: 3000,
        layout: 'top',
        closeWith: ['button', 'click'],
        animation: {
            open: 'in',
            close: 'out',
            easing: 'swing'
        }
	});
};

$(function() {
	// init
	Urban.init();
});
