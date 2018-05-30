var Statistics = Urban.Statistics = {};

// summary
Statistics.Summary = {};
Statistics.Summary.browserplot = null;
Statistics.Summary.clienttopplot = null;
Statistics.Summary.usertopplot = null;
Statistics.Summary.onlinecycle = 0;
Statistics.Summary.onlineplot = null;
Statistics.Summary.loadOnline = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var plot = Statistics.Summary.onlineplot;
    
    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    option.num = 36;
    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Statistics.Server.online(option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
        	var current = data[0];
            //$('.count-online').html(current[1]);
            if(plot) {
            	// redraw
            	plot.setData([data]);
				// Since the axes don't change, we don't need to call plot.setupGrid()
                plot.setupGrid();
				plot.draw();
            } else {
	            plot = Statistics.Summary.onlineplot = $.plot(".stat-sm-online", [ data ], {
	            	colors: [UrbanApp.primary],
	            	grid: {
			            color: UrbanApp.border,
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
                            lineWidth: 0,
                        },
                        splines: {
                            show: true,
                            tension: 0,
                            lineWidth: 1,
                            fill: 0,
                        },
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
            Statistics.Summary.onlinecycle = setTimeout(Statistics.Summary.loadOnline, 60000);
        }
    });
};
Statistics.Summary.loadBrowser = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    
    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
	Statistics.Server.browser(option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
        	var max = data[0];
            //$('.stat-sm-browser').height(180);
            $('.max-browser').html(max.data + '(' + max.label + ')');
            var plot = Statistics.Summary.browserplot = $.plot('.stat-sm-browser', data, {
		        grid: {
		            hoverable: true
		        },
			    tooltip: {
			    	show: true,
			    	//id: 'tooltip',
			        content: "%p.00%, %s", // show percentages, rounding to 2 decimal places
			        shifts: {
			            x: 20,
			            y: 0
			        },
			        defaultTheme: false
			    },
                series: {
                    pie: {
		                show: true,
		                innerRadius: 0.5,
		                stroke: {
		                    width: 2
		                },
		                label: {
		                    show: false,
		                }
                    }
                },
                legend: {
                    show: true
                }
            });
        }
    });
};
Statistics.Summary.loadClientTop = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.num = option.num ? option.num : 5;

    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Statistics.Server.clientTop(option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            //$('.stat-sm-clienttop.chart-sm').height(360);
            var plot = Statistics.Summary.clienttopplot = $.plot('.stat-sm-clienttop.chart-sm', [data], {
		        grid: {
		            hoverable: false,
		            clickable: false,
		            labelMargin: 8,
		            color: UrbanApp.border,
		            borderWidth: 0
		        },
                series: {
                	color: UrbanApp.primary,
                    bars: {
                        show: true,
                        fill: 0.9,
                        horizontal: true,
                        barWidth: 0.5,
                        align: "center"
                    }
                },
                xaxis: {
                	//show: false,
                    //tickSize: 1,
                    tickDecimals: 0,
                    min: 0
                },
                yaxis: {
                    mode: "categories",
                    tickLength: 0
                }
            });
            $('.stat-sm-clienttop.chart-sm').resize(function () {
            	Statistics.fillValueLabel(plot);
			});
            Statistics.fillValueLabel(plot);
        }
    });
};
Statistics.Summary.loadUserTop = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.num = option.num ? option.num : 5;

    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Statistics.Server.userTop(option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            //$('.stat-sm-clienttop.chart-sm').height(360);
            var plot = Statistics.Summary.usertopplot = $.plot('.stat-sm-usertop.chart-sm', [data], {
                grid: {
                    hoverable: false,
                    clickable: false,
                    labelMargin: 8,
                    color: UrbanApp.border,
                    borderWidth: 0
                },
                series: {
                    color: UrbanApp.success,
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
					//tickSize: 1,
					tickDecimals: 0
                }
            });
            $('.stat-sm-usertop.chart-sm').resize(function () {
                Statistics.fillValueLabel(plot, true);
            });
            Statistics.fillValueLabel(plot, true);
        }
    });
};

// client statistics
Statistics.Client = {};
Statistics.Client.clientId = '';
Statistics.Client.clientdateplot = null;
Statistics.Client.weekendAreas = function(axes){
    var markings = [],
    d = new Date(axes.xaxis.min);
    d.setUTCDate(d.getUTCDate());
    d.setUTCSeconds(0);
    d.setUTCMinutes(0);
    d.setUTCHours(0);
    var i = d.getTime();
    do {
        markings.push({ xaxis: { from: i, to: i + 86400 * 1000 } });
        i += 1 * 86400 * 1000;
    } while (i < axes.xaxis.max);
    return markings;
};
Statistics.Client.loadClientDate = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var clientId = $('#clientdate-clientId').val();
    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Statistics.Client.clientId = clientId ? clientId : '';

    Statistics.Server.clientDate(Statistics.Client.clientId, option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            var plot = Statistics.Client.clientdateplot = $.plot(".stat-clientdate.chart", [data], {
                colors: [UrbanApp.primary],
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1,
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
                    color: UrbanApp.border,
                    borderWidth: 1,
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
                    //monthNames: App.Util.monthNames,
                    tickLength: 0
                }
            });
        }
    });
};

// raw log
Statistics.Rawlog = {};
Statistics.Rawlog.clientId = '';
Statistics.Rawlog.datatable = null;
Statistics.Rawlog.dtapi = null;
Statistics.Rawlog.dtoption = {
    language: {
        "url": "/lib/datatables/plug-ins/i18n/Chinese.json"
    }
};
Statistics.Rawlog.loadRawlog = function(clientId, option) {
    // haven't done yet
    option = _.isEmpty(option) ? {} : option;//更多选项
    clientId = clientId ? clientId : '';
    
    Statistics.Rawlog.datatable = $('.datatable').dataTable($.extend(Statistics.Rawlog.dtoption, {
        sAjaxSource : '/admin/ajax/stat/log.json?by=datatable&client_id=' + clientId,
        data: 'logUsers',
        processing: true,
        columns: [{
            class: "align-middle",
            data: "id"
        }, {
            class: "align-middle",
            data: "time"
        }, {
            class: "align-middle",
            data: "clientName"
        }, {
            class: "align-middle",
            data: "clientName"
        }, {
            class: "align-middle",
            data: "success"
        }, {
            class: "align-middle",
            data: "ip"
        }, {
            class: "align-middle",
            data: "os"
        }, {
            class: "align-middle",
            data: "browser"
        }],
        order: [
            [0, 'desc']
        ]
    }));
};
Statistics.Rawlog.loadLog = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var newpKey = 'page';
    var rpKey = 'pageSize';
    var urlstr = Statistics.Server.logURL + '?client_id=' + Statistics.Rawlog.clientId;
    var grid = {
        url: urlstr,
        dataType: 'json',
        colModel : [
            {display: '序号', name : 'id', width : 50, align: 'center' },
            {display: '登录时间', name : 'time', width : 140, align: 'center'},
            {display: '应用名称', name : 'clientName', width : 160,  align: 'center'},
            {display: '帐号', name : 'username', width : 100,  align: 'center'},
            {display: '成功登录', name : 'success', width : 80,  align: 'center'},
            {display: '用户IP', name : 'ip', width : 100,  align: 'center'},
            {display: '操作系统', name : 'os', width : 100,  align: 'center'},
            {display: '浏览器', name : 'browser', width : 100,  align: 'center'}
            ],
        searchitems: [
            {display: '登录时间', name: 'time' },
            {display: '应用名称', name: 'clientName' },
            {display: '帐号', name : 'username' },
            {display: '成功登录', name: 'success' },
            {display: '用户IP', name: 'ip' },
            {display: '操作系统', name: 'os' },
            {display: '浏览器', name: 'browser' }
            ],
        usepager: true,
        useRp: true,
        rp: 15,
        //width: 954,
        height: 360,
        resizable: false,
        autoload: false,
        //title: '用户登录信息日志报表',
        procmsg: '加载中,请稍等...',
        nomsg: '没有信息', 
        pagestat: '显示 {from} ~ {to} 共{total} '
    };
    if(option[newpKey]) { grid.newp = parseInt(option[newpKey],10); }
    if(option[rpKey]) { grid.rp = parseInt(option[rpKey],10); }
    $(".stat-rawlog.grid").flexigrid(grid);
    $(".stat-rawlog.grid").flexReload();
};

// top statistics
Statistics.Top = {};
Statistics.Top.clientId = '';
Statistics.Top.usertopplot = null;
Statistics.Top.clienttopplot = null;
Statistics.Top.loadClientTop = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.num = option.num ? option.num : 10;

    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Statistics.Server.clientTop(option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            //$('.stat-sm-clienttop.chart-sm').height(360);
            var plot = Statistics.Summary.clienttopplot = $.plot('.stat-top-client.chart', [data], {
                grid: {
                    hoverable: false,
                    clickable: false,
                    labelMargin: 8,
                    color: UrbanApp.border,
                    borderWidth: 0
                },
                series: {
                    color: UrbanApp.primary,
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
            $('.stat-top-client.chart').resize(function () {
                Statistics.fillValueLabel(plot);
            });
            Statistics.fillValueLabel(plot);
        }
    });
};
Statistics.Top.loadUserTop = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    option.num = option.num ? option.num : 10;

    var el = option.el ? option.el : false;
    var panel = el ? $(el).closest('.panel') : false;

    App.Util.exists(panel) && $(panel).addClass('panel-refreshing');
    Statistics.Server.userTop(option, function(err, data) {
        App.Util.exists(panel) && $(panel).removeClass('panel-refreshing');
        if(!err) {
            //$('.stat-sm-clienttop.chart-sm').height(360);
            var plot = Statistics.Top.usertopplot = $.plot('.stat-top-user.chart', [data], {
                grid: {
                    hoverable: false,
                    clickable: false,
                    labelMargin: 8,
                    color: UrbanApp.border,
                    borderWidth: 0
                },
                series: {
                    color: UrbanApp.success,
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
            $('.stat-top-user.chart').resize(function () {
                Statistics.fillValueLabel(plot, true);
            });
            Statistics.fillValueLabel(plot, true);
        }
    });

};

// scatter statistics
Statistics.Scatter = {};
Statistics.Scatter.clientId = '';
Statistics.Scatter.loadBrowserD3 = function() {
    // 使用 call,apply
    arguments = [].slice.call(arguments, 1);
    StatD3.loadBrowserD3.apply(this, arguments);
};

// init 
Statistics.init = function() {
    if(App.Util.exists('.select-year')) {
        Statistics.renderSelection();
    }
	if(App.Util.exists('.stat-sm-browser')) {
		Statistics.Summary.loadBrowser();
	}
	if(App.Util.exists('.stat-sm-online')) {
		Statistics.Summary.loadOnline();
	}
	if(App.Util.exists('.stat-sm-clienttop')) {
		Statistics.Summary.loadClientTop();
	}
    if(App.Util.exists('.stat-sm-usertop')) {
        Statistics.Summary.loadUserTop();
    }
    if(App.Util.exists('.stat-rawlog.grid')) {
        Statistics.Rawlog.loadLog();
        //Statistics.Rawlog.loadRawlog();
    }
    if(App.Util.exists('.stat-clientdate.chart')) {
        Statistics.Client.loadClientDate();
    }
    if(App.Util.exists('.stat-browser-d3.chart')) {
        Statistics.Scatter.loadBrowserD3();
    }
    if(App.Util.exists('.stat-top-client.chart')) {
        Statistics.Top.loadClientTop();
    }
    if(App.Util.exists('.stat-top-user.chart')) {
        Statistics.Top.loadUserTop();
    }

	// listen
	Statistics.listen();
};
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
        Statistics.renderDateSelection(year, month, date);
        Statistics.detectDateChange(this);
    });
    $('.select-year').on('change', function() {
        var year = $(this).val();
        var month = $('.select-month', $(this).parent().parent()).val();
        var date = $('.select-date', $(this).parent().parent()).val();
        year = !year ? undefined : parseInt(year);
        month = !month ? undefined : parseInt(month);
        date = !date ? undefined : parseInt(date);
        Statistics.renderDateSelection(year, month, date);
        Statistics.renderMonthSelection(year, month);
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

    var cyear = moment().year();
    var lastmonth;

    year = !year ? cyear : year;
    smonth = !smonth ? 0 : smonth;
    if(year >= cyear) {
        lastmonth = moment().month() + 1;
    } else {
        lastmonth = 12;
    }

    $('.select-month').html('<option value="">月</option>');
    for(var i = 1; i <= lastmonth; i++) {
        $('<option value="' + i + '" ' + (i==smonth?' selected="true"':'') + '>' + i + '</option>').appendTo('.select-month');
    }
};
// year ,month(real month)
Statistics.renderDateSelection = function(year, month, sdate, option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var cyear = moment().year();
    var cmonth = moment().month() + 1;
    var lastday;

    year = !year ? cyear : year;
    month = !month ? cmonth : month;
    sdate = !sdate ? 0 : sdate;
    if(year == cyear && month == cmonth) {
        lastday = moment().date();
    } else {
        lastday = moment().year(year).month(month).date(0).date();
    }

    $('.select-date').html('<option value="">日</option>');
    for(var i = 1; i <= lastday; i++) {
        $('<option value="' + i + '"' + (i==sdate?' selected="true"':'') + '>' + i + '</option>').appendTo('.select-date');
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
    if($(el).hasClass('stat-sm-clienttop')) {
        // 概览中的应用排名,前台暂示展示
        Statistics.Summary.loadClientTop(option);
    } else if($(el).hasClass('stat-sm-usertop')) {
        // 概览中的用户排名,前台暂示展示
        Statistics.Summary.loadUserTop(option);
    } else if($(el).hasClass('stat-top-client')) {
        // 应用排名
        Statistics.Top.loadClientTop(option);
    } else if($(el).hasClass('stat-top-user')) {
        // 用户排名
        Statistics.Top.loadUserTop(option);
    } else if($(el).hasClass('stat-clientdate')) {
        // 应用访问流量
        Statistics.Client.loadClientDate(option);
    } else if($(el).hasClass('stat-browser-d3')) {
        // 浏览器分布D3图,后台暂示实现
        Statistics.Scatter.loadBrowserD3(option);
    }
};
//
Statistics.showTooltip = function(x, y, contents) {
    $('<div id=\'tooltip\'>' + contents + '</div>').css({
        top: y - 10,
        left: x + 20
    }).appendTo('body').fadeIn(200);
};
// fill value label
Statistics.fillValueLabel = function(plot, un) {
    un = un ? true : false;

	var ctx = plot.getCanvas().getContext("2d");
    var data = plot.getData()[0].data;
    var xaxis = plot.getXAxes()[0];
    var yaxis = plot.getYAxes()[0];
    var offset = plot.getPlotOffset();
    ctx.font = UrbanApp.font;
    ctx.fillStyle = UrbanApp.dark;
    for (var i = 0; i < data.length; i++){
        var yPos = 0;
        var xPos = 0;
        var text = '';
        var metrics;

        if(un) {
            text = data[i][1] + '';
            metrics = ctx.measureText(text);
            xPos = (xaxis.p2c(i)+offset.left) - metrics.width/2;
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
            metrics = ctx.measureText(text);
            // TODO 总数是否有可用的API
            var len = App.Util.getNumberLength(data[i][0]);
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

// html
Statistics.Html = {};
Statistics.Html.detectHover = function(el, item) {
	var html = '';
	if($(el).hasClass('stat-sm-online')) {
        var x = item.datapoint[0],
            y = item.datapoint[1];
		html = y + ' at ' + moment(x).format('YYYY-MM-DD HH::mm:ss');
	} else if($(el).hasClass('stat-sm-browser')) {
		var label = item.series.label;
		var per = item.datapoint[0];
		var value = item.datapoint[1][1];
		html = label + '( ' + numeral(per).format('0.00') + '% )';
	} else if($(el).hasClass('stat-sm-clienttop.chart-sm')) {
		var label = item.series.label;
		var per = item.datapoint[0];
		var value = item.datapoint[1][1];
		html = label + '( ' + numeral(per).format('0.00') + '% )';
	}
	return html;
};

// server
Statistics.Server = {};
Statistics.Server.logURL = '/admin/ajax/stat/log.json';
Statistics.Server.topURL = '/admin/ajax/stat/top.json';
Statistics.Server.visitURL = '/admin/ajax/stat/visit.json';
Statistics.Server.onlineURL = '/admin/ajax/stat/online.json';
//Statistics.Server.browserURL = '/admin/ajax/stat/top.json';
//Statistics.Server.browserD3URL = '/admin/ajax/stat/top.json';
Statistics.Server.statURL = '/admin/ajax/stat/stat.json';
Statistics.Server.browser = function(option, fn) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数

    var data = {};
    data.key = 'browser_top';
    $.ajax({
        url: Statistics.Server.topURL,
        type: 'post',
        data: data,
        timeout: 90000,
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
Statistics.Server.clientDate = function(clientId, option, fn) {
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
};
Statistics.Server.loadRawlog = function(clientId, option, fn) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    fn = _.isFunction(fn) ? fn : function() {};//回调函数
    option.beforeSend = _.isFunction(option.beforeSend) ? option.beforeSend : function() {};//beforeSend回调函数
    option.complete = _.isFunction(option.complete) ? option.complete : function() {};//complete回调函数
    option.error = _.isFunction(option.error) ? option.error : function() {};//error回调函数
    clientId = clientId ? clientId : '';

    $.ajax({
        url : Statistics.Server.logURL,
        type :'post',
        data:{
            client_id:clientId
        },
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

$(function() {
	if(!App.Util.exists('.main-content.admin-statistics')) {
		return;
	}

	Statistics.init();
});
