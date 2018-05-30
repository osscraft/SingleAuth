var Statistics = Portal.Statistics = {};

Statistics.onlinecycle = 0;
Statistics.onlineplot = null;
// init
Statistics.init = function() {
	$("#stat-online").height(200);
	$('#stat-client-top').height(360);
	$('#stat-user-top').height(360);
	$('#stat-clientDate').height(360);
};
Statistics.load = function() {
	// 清除在线用户请求循环
	if(Statistics.onlinecycle) {
		clearTimeout(Statistics.onlinecycle);
	}
	//
	if(App.Util.exists('#stat-online')) {
		Statistics.loadOnline();
	}
	if(App.Util.exists('#stat-client-top')) {
		Statistics.loadClientTop();
	}
    if(App.Util.exists('#stat-user-top')) {
		//Statistics.Server.userTop(null,null);
        Statistics.loadUserTop();
    }
	if(App.Util.exists('.select-year')) {
        Statistics.renderSelection();
    }
	if(App.Util.exists('#stat-clientDate')) {
		//Statistics.loadClients();
        Statistics.loadClientDate();
    }
	Statistics.listen();
};
Statistics.fillValueLabel = function(plot, un) {
    un = un ? true : false;

	var ctx = plot.getCanvas().getContext("2d");
    var data = plot.getData()[0].data;
    var xaxis = plot.getXAxes()[0];
    var yaxis = plot.getYAxes()[0];
    var offset = plot.getPlotOffset();
    ctx.font = App.font;
    ctx.fillStyle = App.dark;
    for (var i = 0; i < data.length; i++){
        var yPos = 0;
        var xPos = 0;
        var text = '';
        var metrics;
        var len;

        if(un) {
            text = data[i][1] + '';
            //metrics = ctx.measureText(text);
            len = App.Util.getNumberLength(data[i][1]);
            xPos = (xaxis.p2c(i)+offset.left) - 7*len/2;//metrics.width
            if(yaxis.p2c(data[i][1])>=75){
                yPos = yaxis.p2c(data[i][1]) + offset.top - 5;
            } else {
                yPos = yaxis.p2c(data[i][1]) + offset.top + 10;
            }
            //console.log(yaxis, data[i][1], yaxis.p2c(data[i][1]), offset.top);
            yPos = yaxis.p2c(data[i][1]) + offset.top - 5;
        } else {
            var j = 0;
            var count = 1;
            text = data[i][0] + '';
            //metrics = ctx.measureText(text);
            // TODO 总数是否有可用的API
            len = App.Util.getNumberLength(data[i][0]);
            if(xaxis.p2c(data[i][0])<=15){
                xPos = xaxis.p2c(data[i][0]) + offset.left + 5;
            }else{
                xPos = xaxis.p2c(data[i][0]) + offset.left -7*len;
            }
            yPos = (yaxis.p2c(i)+offset.top) - 12/2 + 10;
        }
        ctx.fillText(text, xPos, yPos);
    }
};
Statistics.loadClientTop = function(option) {
	option = _.isEmpty(option) ? {} : option;//更多选项
    option.num = option.num ? option.num : 10;
	$('.loader').show();
    Statistics.Server.clientTop(option, function(err, data) {
		$('.loader').hide();
        if(!err) { 
            var plot = $.plot('#stat-client-top', [data], {
                grid: {
                    hoverable: false,
                    clickable: false,
                    labelMargin: 8,
                    color: App.border,
                    borderWidth: 0
                },
                series: {
                    color: App.primary,
                    bars: {
                        show: true,
                        horizontal: true,
                        fill: 0.9,
                        barWidth: 0.5,
                        align: "center"
                    }
                },
                xaxis: {
                    //show: false,
                    tickDecimals: 0,
                    min: 0
                },
                yaxis: {
                    mode: "categories",
                    tickLength: 0
                }
            });
            $('#stat-client-top').resize(function () {
                Statistics.fillValueLabel(plot);
            });
            Statistics.fillValueLabel(plot);
        }
    });
};
Statistics.loadClientDate = function(option) {
	option = _.isEmpty(option) ? {} : option;//更多选项

    var clientId = $('#clientdate-clientId').val();
    Statistics.clientId = clientId ? clientId : '';
	$('.loader').show();
    Statistics.Server.clientDate(Statistics.clientId, option, function(err, data) {
		$('.loader').hide();
        if(!err) {
			var year = option.year == undefined?'':option.year;
			var month = option.month == undefined?'':option.month;
			var date = option.date == undefined?'':option.date;
			var timeformat = "%Y/%m/%d";
			if(date != ''){
				timeformat = '%H:%M';
			}else if(month != ''){
				timeformat = '%m/%d';
			}
			//var timeformat = "%Y/%m/%d";
            var plot = $.plot("#stat-clientDate", [data], {
                colors: [App.standard],
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1
                    },
                    /*splines: {
                        show: true,
                        tension: 0,
                        lineWidth: 1,
                        fill: 0.2,
                    },*/
                    shadowSize: 0
                },
                grid: {
                    //markings: Statistics.Client.weekendAreas,
                    color: App.border,
                    borderWidth: 0,
                    hoverable: true
                },
                tooltip: {
                    show: true,
                    content: "%y, %x", // show percentages, rounding to 2 decimal places
                    shifts: {
                        x: 20,
                        y: -10
                    },
                    defaultTheme: false
                },
                yaxis: {
                    min: 0,
					//tickSize: 1,
					tickDecimals: 0
                },
                xaxis: {
                    mode: "time",
                    timezone: "browser",
					timeformat: timeformat,
                    //monthNames: App.Util.monthNames,
                    tickLength: 0
                }
            });
        }
    });
};
Statistics.loadUserTop = function(option) {
	option = _.isEmpty(option) ? {} : option;//更多选项
    option.num = option.num ? option.num : 10;
	$('.loader').show();
    Statistics.Server.userTop(option, function(err, data) {
		$('.loader').hide();
        if(!err) {
            var plot = $.plot('#stat-user-top', [data], {
                grid: {
                    hoverable: false,
                    clickable: false,
                    labelMargin: 8,
                    color: App.border,
                    borderWidth: 0
                },
                series: {
                    color: App.success,
                    bars: {
                        show: true,
                        fill: 0.9,
                        barWidth: 0.5,
                        align: "center"
                    }
                },
                xaxis: {
                    //show: false,
                    mode: "categories",
                    tickLength: 0
                },
                yaxis: {
					tickDecimals: 0,
					min: 0
                }
            });
            $('#stat-user-top').resize(function () {
                Statistics.fillValueLabel(plot, true);
            });
            Statistics.fillValueLabel(plot, true);
        }
    });
};
Statistics.loadOnline = function(option) {
	option = _.isEmpty(option) ? {} : option;//更多选项

    var plot = Statistics.onlineplot;
    var interval = config.online_interval ? config.online_interval : 10000;

    option.num = config.online_num ? config.online_num : 60;

    if(!$('.switcher.statistics').parent('.switcher-item').hasClass('on')) {
    	//当前不是统计TAB下
    	return;
    }
    // show
    $('.loader').show();
    //App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Statistics.Server.online(option, function(err, data) {
    	$('.loader').hide();
        //App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            if(plot) {
            	// redraw
            	plot.setData([data]);
				// Since the axes don't change, we don't need to call plot.setupGrid()
                plot.setupGrid();
				plot.draw();
            } else {
	            plot = Statistics.onlineplot = $.plot("#stat-online", [ data ], {
	            	colors: [App.standard],
	            	grid: {
			            color: App.border,
			            borderWidth: 0,
			            hoverable: true
	            	},
				    tooltip: {
				    	show: true,
				    	//id: 'tooltip',
				        content: "%y, %x", // show percentages, rounding to 2 decimal places
				        shifts: {
				            x: 20,
				            y: -10
				        },
				        defaultTheme: false
				    },
	                series: {
                        lines: {
                            show: true,
                            lineWidth: 1
                        },
                        /*splines: {
                            show: true,
                            tension: 0.2,
                            lineWidth: 1,
                            fill: 0,
                        },*/
	                    shadowSize: 0   // Drawing is faster without shadows
	                },
	                yaxis: {
	                    min: 0,
						//tickSize: 1,
						tickDecimals: 0
	                },
	                xaxis: {
	                    show: false,
	                    //tickLength: 5,
	                    timezone: "browser",
	                    timeformat: '%H:%M:%S',
	                    mode: "time"
	                }
	            });
            }
            Statistics.onlinecycle = setTimeout(Statistics.loadOnline, interval);
        }
    });
};

Statistics.Html = {};
Statistics.Server = {};
Statistics.Server.topURL = '/portal/top.json';
Statistics.Server.onlineURL = '/portal/online.json';
Statistics.Server.visitURL = '/portal/clientDate.json';
Statistics.Server.online = function(option, fn) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数

    var data = {};
    if(option.num) {
        data.num = option.num;
    }
    $.ajax({
        url : Statistics.Server.onlineURL,
        type :'post',
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
Statistics.Server.clientTop = function(option, fn) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数

    var data = {};

    data.key = 'client_top';
    data.num = option.num ? option.num : 5;
    data.year = option.year ? option.year : '';
    data.month = option.month ? option.month : '';
    data.date = option.date ? option.date : '';
    $.ajax({
        url: Statistics.Server.topURL,
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
Statistics.Server.userTop = function(option, fn) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数

    var data = {};

    data.key = 'user_top';
    data.num = option.num ? option.num : 5;
    data.year = option.year ? option.year : '';
    data.month = option.month ? option.month : '';
    data.date = option.date ? option.date : '';
    $.ajax({
        url: Statistics.Server.topURL,
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
Statistics.Server.clientDate = function(clientId, option, fn){
	option = _.isEmpty(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数

    var data = {};

    data.client_id = clientId ? clientId : '';
	data.year = option.year ? option.year : '';
    data.month = option.month ? option.month : '';
    data.date = option.date ? option.date : '';
    $.ajax({
        url: Statistics.Server.visitURL,
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
}

Statistics.listen = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    $('.select-clientid').on('change', function() {
        Statistics.detectDateChange(this);
    });
    $('.select-date').on('change', function() {
        //Statistics.Client.loadClientDate();
        Statistics.detectDateChange(this);
    });
    $('.select-month').on('change', function() {
        //Statistics.Client.loadClientDate();
        var month = $(this).val();
        var year = $('.select-year', $(this).parent().parent()).val();
        var date = $('.select-date', $(this).parent().parent()).val();
        year = !year ? undefined : parseInt(year);
        month = !month ? undefined : parseInt(month);
        date = !date ? undefined : parseInt(date);
		var option = {};
		option.container = $(this);
        Statistics.renderDateSelection(year, month, date, option);
        Statistics.detectDateChange(this);
    });
    $('.select-year').on('change', function() {
        var year = $(this).val();
        var month = $('.select-month', $(this).parent().parent()).val();
        var date = $('.select-date', $(this).parent().parent()).val();
        year = !year ? undefined : parseInt(year);
        month = !month ? undefined : parseInt(month);
        date = !date ? undefined : parseInt(date);
		var option = {};
		option.container = $(this);
        Statistics.renderDateSelection(year, month, date, option);
        Statistics.renderMonthSelection(year, month, option);
        Statistics.detectDateChange(this);
    });
};
// render html
Statistics.renderSelection = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
	
    Statistics.renderYearSelection();
    Statistics.renderMonthSelection();
    Statistics.renderDateSelection();
};
Statistics.Server.clientsURL = '/portal/clients.json';
Statistics.Server.Clients = function(option, fn){
    option = _.isEmpty(option) ? {} : option;//更多选项
	fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数
	$.ajax({
		url: Statistics.Server.clientsURL,
		type:'post',
		//data: data,
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
Statistics.loadClients = function(option){
    option = _.isEmpty(option) ? {} : option;//更多选项
    
	$('#clientdate-clientId').html('<option value="">选择应用</option>');
	Statistics.Server.Clients(option, function(err,data){
		if(!err){
			for(var i = 0; i < data.length; i++) {
				$('<option value="' + data[i].clientId + '" >' + data[i].clientName + '</option>').appendTo('#clientdate-clientId');
			}
		}
    });
}
Statistics.renderYearSelection = function(syear, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    var cyear = moment().year();
    var len = option.len ? option.len : 3;
    syear = !syear ? 0 : syear;
    $('.select-year').html('<option value="">年</option>');
    for(var i = 0; i < len; i++) {
        $('<option value="' + (cyear - i) + '"' + (cyear-i==syear?' selected="true"':'') + '>' + (cyear - i) + '</option>').appendTo('.select-year');
    }
};
Statistics.renderMonthSelection = function(year, smonth, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
	var classes = '';
	if(option.container!=undefined){
		classes = option.container.hasClass('stat-top-client')==true?'.stat-top-client':(option.container.hasClass('stat-top-user')==true?'.stat-top-user':option.container.hasClass('stat-clientdate')==true?'.stat-clientdate':'');
	}
    var cyear = moment().year();
    var lastmonth;
    year = !year ? cyear : year;
    smonth = !smonth ? 0 : smonth;
    if(year >= cyear) {
        lastmonth = moment().month() + 1;
    } else {
        lastmonth = 12;
    }

    $(classes+'.select-month').html('<option value="">月</option>');
    for(var i = 1; i <= lastmonth; i++) {
        $('<option value="' + i + '" ' + (i==smonth?' selected="true"':'') + '>' + i + '</option>').appendTo(classes+'.select-month');
    }
};
// year ,month(real month)
Statistics.renderDateSelection = function(year, month, sdate, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var cyear = moment().year();
    var cmonth = moment().month() + 1;
    var lastday;
	var classes = '';
	if(option.container!=undefined){
		classes = option.container.hasClass('stat-top-client')==true?'.stat-top-client':(option.container.hasClass('stat-top-user')==true?'.stat-top-user':option.container.hasClass('stat-clientdate')==true?'.stat-clientdate':'');
	}
    year = !year ? cyear : year;
    month = !month ? cmonth : month;
    sdate = !sdate ? 0 : sdate;
    if(year == cyear && month == cmonth) {
        lastday = moment().date();
    } else {
        lastday = moment().year(year).month(month).date(0).date();
    }
    $(classes+'.select-date').html('<option value="">日</option>');
    for(var i = 1; i <= lastday; i++) {
        $('<option value="' + i + '"' + (i==sdate?' selected="true"':'') + '>' + i + '</option>').appendTo(classes+'.select-date');
    }
    //$('.stat-clientdate-select-year').append
};
//detect change
Statistics.detectDateChange = function(el) {
    var year = $('.select-year', $(el).parent().parent()).val();
    var month = $('.select-month', $(el).parent().parent()).val();
    var date = $('.select-date', $(el).parent().parent()).val();
    var option = {};
    option.el = el;
    option.year = !year ? '' : parseInt(year);
    option.month = !month ? '' : parseInt(month);
    option.date = !date ? '' : parseInt(date);
    // 
    if($(el).hasClass('stat-top-client')) {
        // 应用排名
        Statistics.loadClientTop(option);
    } else if($(el).hasClass('stat-top-user')) {
        // 用户排名
        Statistics.loadUserTop(option);
    } else if($(el).hasClass('stat-clientdate')) {
        // 应用访问次数
        Statistics.loadClientDate(option);
    } 
};


$(function() {
	if(App.Util.exists('.portal-statistics')) {
		Portal.Statistics.init();
	}
});