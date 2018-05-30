//
var Stat = Admin.Stat = {};

$(document).ready(function() {
    var option = {};
    var clientTop = $('#client_top');
    var clientDate = $('#client_date');
    var browserTop = $('#browser_top');
    var browserD3 = $('#browser_d3');
    var stat = $('#stat');
    var online = $('#online');
    var clientId = $('#client_id').val();
    if(clientId) {
        Stat.clientId = clientId;
    }
    if(stat.length > 0) {
        Stat.loadLog(option);
    }
    if(clientTop.length > 0) {
        Stat.loadClientTop(option);
    }
    if(clientDate.length > 0) {
        Stat.loadClientDate(option);
    }
    if(browserTop.length > 0) {
        Stat.loadBrowserTop(option);
    }
    if(browserD3.length > 0) {
        Stat.loadBrowserD3(option);
    }
    if(online.length > 0) {
        Stat.loadOnline(option);
    }
});

Stat.clientId = '';
Stat.loadLog = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    var newpKey = 'page';
    var rpKey = 'pageSize';
    var urlstr = '/admin/stat/log.php?client_id=' + Stat.clientId;
    var grid = {
        url: urlstr,
        dataType: 'json',
        colModel : [
            {display: '序号', name : 'id', width : 50, align: 'center' },
            {display: '登录时间', name : 'time', width : 140, align: 'center'},
            //{display: 'ClientId', name : 'clientId', width : 180, align: 'left',hide: true},
            //{display: 'FacilityHost', name : 'facilityHost', width : 180,  align: 'left',hide: true},
            {display: '应用名称', name : 'clientName', width : 160,  align: 'center'},
            {display: '帐号', name : 'username', width : 100,  align: 'center'},
            //{display: 'Success', name : 'success', width : 80,  align: 'center',hide: true},
            {display: '成功登录', name : 'success', width : 80,  align: 'center'},
            {display: '用户IP', name : 'ip', width : 100,  align: 'center'},
            {display: '操作系统', name : 'os', width : 100,  align: 'center'},
            {display: '浏览器', name : 'browser', width : 100,  align: 'center'}
            ],
        searchitems: [
            {display: '登录时间', name: 'timeReported' },
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
        width: 954,
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
    $("#stat").flexigrid(grid);
    $("#stat").flexReload();
};
Stat.loadBrowserTop = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    this.browserTop({}, option, function(err, data) {
        if(!err) {
            $('#browser_top').height(400);
            $.plot('#browser_top', data, {
                series: {
                    pie: {
                        show: true,
                        /*radius: 1,
                        label: {
                            show: true,
                            radius: 2/3,
                            //formatter: labelFormatter,
                            threshold: 0.1
                        }*/
                    }
                },
                legend: {
                    show: false
                }
            });
        }
    });
};
Stat.browserTop = function(cond, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    $.ajax({
        url : "/admin/stat/stat.php?key=browser_top",
        type :'post',
        data:{
        },
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(o.msg);
            }
        },
        complete:function(){
        },
        error:function(){
            //alert('login failure!');
            //$("#LoginErrorID").html('登录失败!要不再来次');
        }
    });
};
Stat.loadBrowserD3 = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    // Dimensions of sunburst.
    var width = 954;
    var height = 500;
    var radius = Math.min(width, height) / 2;

    // Breadcrumb dimensions: width, height, spacing, width of tip/tail.
    var b = {
      w: 75, h: 30, s: 3, t: 10
    };

    // Mapping of step names to colors.
    var colors = {
        "Chrome": "#1f77b4",
        "Firfox": "#ff7f0e",
        "Safari": "#2ca02c"
        /*"home": "#5687d1",
        "product": "#7b615c",
        "search": "#de783b",
        "account": "#6ab975",
        "other": "#a173d1",
        "end": "#bbbbbb"*/
    };
    var hue = d3.scale.category20();

    var luminance = d3.scale.sqrt()
        .domain([0, 1e6])
        .clamp(true)
        .range([90, 20]);

    // Total size of all segments; we set this later, after loading the data.
    var totalSize = 0; 

    var vis = d3.select("#browser_d3").append("svg:svg")
        .attr("width", width)
        .attr("height", height)
        .append("svg:g")
        .attr("id", "container")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var partition = d3.layout.partition()
        .size([2 * Math.PI, radius * radius])
        .value(function(d) { return d.size; });

    var arc = d3.svg.arc()
        .startAngle(function(d) { return d.x; })
        .endAngle(function(d) { return d.x + d.dx; })
        .innerRadius(function(d) { return Math.sqrt(d.y); })
        .outerRadius(function(d) { return Math.sqrt(d.y + d.dy); });

    // Use d3.text and d3.csv.parseRows so that we do not need to have a header
    // row, and can receive the csv as an array of arrays.
    d3.json("/admin/stat/stat.json?key=browser_d3", function(err, json) {
        //var csv = d3.csv.parseRows(text);
        //var json = buildHierarchy(csv);
        if(!err) {
          json = json.data;
          createVisualization(json);
        }
    });

    // Main function to draw and set up the visualization, once we have the data.
    function createVisualization(json) {

        // Basic setup of page elements.
        initializeBreadcrumbTrail();
        //drawLegend();
        d3.select("#togglelegend").on("click", toggleLegend);

        // Bounding circle underneath the sunburst, to make it easier to detect
        // when the mouse leaves the parent g.
        vis.append("svg:circle")
            .attr("r", radius)
            .style("opacity", 0);

        // Compute the initial layout on the entire tree to sum sizes.
        // Also compute the full name and fill color for each node,
        // and stash the children so they can be restored as we descend.
        partition
            .value(function(d) { return d.size; })
            .nodes(json)
            //.append("svg:text")
            //.text(function(d) {return d.name;})
            .forEach(function(d) {
                d._children = d.children;
                d.sum = d.value;
                d.key = key(d);
                d.fill = fill(d);
            });
        // Now redefine the value function to use the previously-computed sum.
        partition
            .children(function(d, depth) { return depth < 2 ? d._children : null; })
            .value(function(d) { return d.sum; });

        // For efficiency, filter nodes to keep only those large enough to see.
        var nodes = partition.nodes(json)
            .filter(function(d) {
            return (d.dx > 0.005); // 0.005 radians = 0.29 degrees
        });

        var path = vis.data([json]).selectAll("path")
            .data(nodes)
            .enter().append("svg:path")
            .attr("display", function(d) { return d.depth ? null : "none"; })
            .attr("d", arc)
            .attr("fill-rule", "evenodd")
            .style("fill", function(d) {
                var ver = parseInt(d.name);
                var color = d.fill;
                if(d.name && isNaN(ver) && !colors[d.name]) {
                    colors[d.name] = color;
                }
                return color;
            })
            .style("opacity", 1)
            .on("mouseover", mouseover);

        // Add the mouseleave handler to the bounding circle.
        d3.select("#container").on("mouseleave", mouseleave);

        // Get total size of the tree = value of root node from partition.
        totalSize = path.node().__data__.value;
    };

    // Fade all but the current sequence, and show it in the breadcrumb trail.
    function mouseover(d) {

        var percentage = (100 * d.value / totalSize).toPrecision(3);
        var percentageString = percentage + "%";
        if (percentage < 0.1) {
            percentageString = "< 0.1%";
        }

        d3.select("#percentage")
            .text(percentageString);

        d3.select("#explanation")
            .style("visibility", "");

        var sequenceArray = getAncestors(d);
        updateBreadcrumbs(sequenceArray, percentageString);

        // Fade all the segments.
        d3.selectAll("path")
            .style("opacity", 0.3);

        // Then highlight only those that are an ancestor of the current segment.
        vis.selectAll("path")
            .filter(function(node) {
                return (sequenceArray.indexOf(node) >= 0);
            })
            .style("opacity", 1);
    }

    // Restore everything to full opacity when moving off the visualization.
    function mouseleave(d) {
        // Hide the breadcrumb trail
        d3.select("#trail")
            .style("visibility", "hidden");
        // Deactivate all segments during transition.
        d3.selectAll("path").on("mouseover", null);
        // Transition each segment to full opacity and then reactivate it.
        d3.selectAll("path")
            .transition()
            .duration(100)
            .style("opacity", 1)
            .each("end", function() {
                d3.select(this).on("mouseover", mouseover);
            });
        d3.select("#explanation")
            .style("visibility", "hidden");
    }

    // Given a node in a partition layout, return an array of all of its ancestor
    // nodes, highest first, but excluding the root.
    function getAncestors(node) {
        var path = [];
        var current = node;
        while (current.parent) {
            path.unshift(current);
            current = current.parent;
        }
        return path;
    }

    function initializeBreadcrumbTrail() {
        // Add the svg area.
        var trail = d3.select("#sequence").append("svg:svg")
            .attr("width", width)
            .attr("height", 50)
            .attr("id", "trail");
        // Add the label at the end, for the percentage.
        trail.append("svg:text")
            .attr("id", "endlabel")
            .style("fill", "#000");
    }

    // Generate a string that describes the points of a breadcrumb polygon.
    function breadcrumbPoints(d, i) {
        var points = [];
        points.push("0,0");
        points.push(b.w + ",0");
        points.push(b.w + b.t + "," + (b.h / 2));
        points.push(b.w + "," + b.h);
        points.push("0," + b.h);
        if (i > 0) { // Leftmost breadcrumb; don't include 6th vertex.
            points.push(b.t + "," + (b.h / 2));
        }
        return points.join(" ");
    }

    // Update the breadcrumb trail to show the current sequence and percentage.
    function updateBreadcrumbs(nodeArray, percentageString) {
        // Data join; key function combines name and depth (= position in sequence).
        var g = d3.select("#trail")
            .selectAll("g")
            .data(nodeArray, function(d) { return d.name + d.depth; });
        // Add breadcrumb and label for entering nodes.
        var entering = g.enter().append("svg:g");
        entering.append("svg:polygon")
            .attr("points", breadcrumbPoints)
            .style("fill", function(d) { return colors[d.name] ? colors[d.name] : colors[d.parent.name]; });

        entering.append("svg:text")
            .attr("x", (b.w + b.t) / 2)
            .attr("y", b.h / 2)
            .attr("dy", "0.35em")
            .attr("text-anchor", "middle")
            .text(function(d) { return d.name; });
        // Set position for entering and updating nodes.
        g.attr("transform", function(d, i) {
            return "translate(" + i * (b.w + b.s) + ", 0)";
        });
        // Remove exiting nodes.
        g.exit().remove();
        // Now move and update the percentage at the end.
        d3.select("#trail").select("#endlabel")
            .attr("x", (nodeArray.length + 0.5) * (b.w + b.s))
            .attr("y", b.h / 2)
            .attr("dy", "0.35em")
            .attr("text-anchor", "middle")
            .text(percentageString);
        // Make the breadcrumb trail visible, if it's hidden.
        d3.select("#trail")
            .style("visibility", "");
    }

    function drawLegend() {
        // Dimensions of legend item: width, height, spacing, radius of rounded rect.
        var li = {
            w: 75, h: 30, s: 3, r: 3
        };
        var legend = d3.select("#legend").append("svg:svg")
            .attr("width", li.w)
            .attr("height", d3.keys(colors).length * (li.h + li.s));
        var g = legend.selectAll("g")
            .data(d3.entries(colors))
            .enter().append("svg:g")
            .attr("transform", function(d, i) {
                return "translate(0," + i * (li.h + li.s) + ")";
            });
        g.append("svg:rect")
            .attr("rx", li.r)
            .attr("ry", li.r)
            .attr("width", li.w)
            .attr("height", li.h)
            .style("fill", function(d) { return d.value; });
        g.append("svg:text")
            .attr("x", li.w / 2)
            .attr("y", li.h / 2)
            .attr("dy", "0.35em")
            .attr("text-anchor", "middle")
            .text(function(d) { return d.key; });
    }

    function toggleLegend() {
        var legend = d3.select("#legend");
        if (legend.style("visibility") == "hidden") {
            legend.style("visibility", "");
        } else {
            legend.style("visibility", "hidden");
        }
    }

    // Take a 2-column CSV and transform it into a hierarchical structure suitable
    // for a partition layout. The first column is a sequence of step names, from
    // root to leaf, separated by hyphens. The second column is a count of how 
    // often that sequence occurred.
    function buildHierarchy(csv) {
        var root = {"name": "root", "children": []};
        for (var i = 0; i < csv.length; i++) {
            var sequence = csv[i][0];
            var size = +csv[i][1];
            if (isNaN(size)) { // e.g. if this is a header row
                continue;
            }
            var parts = sequence.split("-");
            var currentNode = root;
            for (var j = 0; j < parts.length; j++) {
                var children = currentNode["children"];
                var nodeName = parts[j];
                var childNode;
                if (j + 1 < parts.length) {
                    // Not yet at the end of the sequence; move down the tree.
                    var foundChild = false;
                    for (var k = 0; k < children.length; k++) {
                        if (children[k]["name"] == nodeName) {
                            childNode = children[k];
                            foundChild = true;
                            break;
                        }
                    }
                    // If we don't already have a child node for this branch, create it.
                    if (!foundChild) {
                        childNode = {"name": nodeName, "children": []};
                        children.push(childNode);
                    }
                    currentNode = childNode;
                } else {
                    // Reached the end of the sequence; create a leaf node.
                    childNode = {"name": nodeName, "size": size};
                    children.push(childNode);
                }
            }
        }
        return root;
    }
    function key(d) {
        var k = [], p = d;
        while (p.depth) k.push(p.name), p = p.parent;
        return k.reverse().join(".");
    }

    function fill(d) {
        var p = d;
        while (p.depth > 1) p = p.parent;
        var c = d3.lab(hue(p.name));
        if(colors[p.name]) {
            c = d3.rgb(colors[p.name]);
        }
        c.l = luminance(d.sum);
        return c;
    }
};
Stat.browserD3 = function(cond, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    $.ajax({
        url : "/admin/stat/stat.php?key=browser_d3",
        type :'post',
        data:{
        },
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(o.msg);
            }
        },
        complete:function(){
        },
        error:function(){
            //alert('login failure!');
            //$("#LoginErrorID").html('登录失败!要不再来次');
        }
    });
};
Stat.loadClientTop = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    this.clientTop({}, option, function(err, data) {
        if(!err) {
            $('#client_top').height(400);
            $.plot('#client_top', [data], {
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        align: "center"
                    }
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0
                }
            });
        }
    });
};
Stat.clientTop = function(cond, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    $.ajax({
        url : "/admin/stat/stat.php?key=client_top",
        type :'post',
        data:{
        },
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(o.msg);
            }
        },
        complete:function(){
        },
        error:function(){
            //alert('login failure!');
            //$("#LoginErrorID").html('登录失败!要不再来次');
        }
    });
};
Stat.loadClientDate = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    this.clientDate({}, option, function(err, data) {
        if(!err) {
            $('#client_date').height(400);
      var d=data;
      weekendAreas=function(axes){
        var markings = [],
        d = new Date(axes.xaxis.min);
        d.setUTCDate(d.getUTCDate());
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();
        do {
          markings.push({ xaxis: { from: i, to: i + 10 * 60 * 60 * 1000 } });
          i += 1 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);
        return markings;
      } 
      var options = {
        xaxis: {
          mode: "time",
          tickLength: 5
        },
        selection: {
          mode: "x"
        },
        grid: {
          markings: weekendAreas
        }
      };
      var plot = $.plot("#client_date", [d], options);
            $("#client_date").bind("plotselected", function (event, ranges) {
      $.each(plot.getXAxes(), function(_, axis) {
        var opts = axis.options;
        opts.min = ranges.xaxis.from;
        opts.max = ranges.xaxis.to;
      });
      plot.setupGrid();
      plot.draw();
      plot.clearSelection();
      overview.setSelection(ranges, true);
    });
        }
    });
};
Stat.clientDate = function(cond, option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    $.ajax({
        url : "/admin/stat/visitor.php?client_id=" + Stat.clientId,
        type :'post',
        data:{
        },
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(o.msg);
            }
        },
        complete:function(){
        },
        error:function(){
        }
    });
};
Stat.loadOnline = function(option) {
    option = _.isEmpty(option) ? {} : option;//更多选项

    Stat.online(option, function(err, data) {
        if(!err) {
            $('#online').height(400);
            $.plot("#online", [ data ], {
                series: {
                    shadowSize: 0   // Drawing is faster without shadows
                },
                yaxis: {
                    min: 0
                },
                xaxis: {
                    show: false
                    //mode: "time"
                }
            });
            setTimeout(Stat.loadOnline, 5000);
        }
    });
};
Stat.online = function(option, callback) {
    option = _.isEmpty(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    $.ajax({
        url : "/admin/stat/online.php",
        type :'post',
        data:{
        },
        timeout:90000,
        beforeSend:function(){
        },
        success:function(o){
            //var o = eval("("+str+")");
            if(o.code == 0) {
                callback(null, o.data);
            }else{
                alert(o.msg);
            }
        },
        complete:function(){
        },
        error:function(){
        }
    });
};
