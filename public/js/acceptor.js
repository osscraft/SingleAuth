//父子iframe回调接收器，有一定的协议规则
var Acceptor = App.Acceptor = {};
/**
 * 处理接收器，通过是否是子域或其他来重导向App.Acceptor.fromChild、App.Acceptor.fromSelf、App.Acceptor.fromParent等等
 * @param string|int id ID
 * @param string type 标识接收器处理类型
 * @param Object data 数据
 * @param Object option 选项
 * @param Function callback 回调函数
 */
Acceptor.inspire = function(id, type, data, option, callback) {
    if(!_.isUndefined(window.parent) && window.parent) {
        id = id ? id : window.name;
        window.parent.App.Acceptor.fromChild(id, type, data, option, callback);
    } else {
        id = id ? id : window.name;
        window.App.Acceptor.fromSelf(id, type, data, option, callback);
    }
};
/**
 * 本域接收器
 * @param string|int self_id ID
 * @param string type 标识接收器处理类型
 * @param Object data 数据
 * @param Object option 选项
 * @param Function callback 回调函数
 */
Acceptor.fromSelf = function(self_id, type, data, option, callback) {
    data = _.isObject(data) ? data : {};
    data.args = _.isArray(data.args) ? data.args : [];//一些返回类型是函数名时携带的一些参数
    data.cmd = _.isString(data.cmd) ? data.cmd : '';//cmd
    data.data = _.isObject(data.data) ? data.data : {};//当返回类型是callback时cmd下的一些数据
    option = _.isUndefined(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数

    //根据不同返回类型处理
    if(type == 'alert') {//要求弹出信息
        if(data.success) {
            App.Alt.successInfo(3, data.args[0], 1);
        } else {
            App.Alt.warningInfo(3, data.args[0], 1);
        }
    } else if(type == 'history.go') {//要求返回
        if(data.args[0]) {
            setTimeout(function() {
                history.go(data.args[0]);
            }, 1000);
        }
    } else if(type == 'location.href') {//要求跳转
        if(data.args[0]) {
            setTimeout(function() {
                location.href = data.args[0];
            }, 1000);
        }
    } else if(type == 'callback') {//当返回类型是'callback'时
        location.href = _.isString(data.href) ? '/' : data.href;
    } else {
        //什么也不做
    }
    callback();
};
/**
 * 子域接收器
 * @param string|int child_id ID
 * @param string type 标识接收器处理类型
 * @param Object data 数据
 * @param Object option 选项
 * @param Function callback 回调函数
 */
Acceptor.fromChild = function(child_id, type, data, option, callback) {
    data = _.isObject(data) ? data : {};
    data.args = _.isArray(data.args) ? data.args : [];//一些返回类型是函数名时携带的一些参数
    data.cmd = _.isString(data.cmd) ? data.cmd : '';//cmd
    data.data = _.isObject(data.data) ? data.data : {};//当返回类型是callback时cmd下的一些数据
    option = _.isUndefined(option) ? {} : option;//更多选项
    callback = _.isFunction(callback) ? callback : function() {};//回调函数
    
    //根据不同返回类型处理
    if(type == 'alert') {//要求弹出信息
        if(data.success) {
            App.Alt.successInfo(3, data.args[0], 1);
        } else {
            App.Alt.warningInfo(3, data.args[0], 1);
        }
    } else if(type == 'history.go') {//要求返回
        if(data.args[0]) {
            setTimeout(function() {
                history.go(data.args[0]);
            }, 3000);
        }
    } else if(type == 'location.href') {//要求跳转
        if(data.args[0]) {
            setTimeout(function() {
                location.href = data.args[0];
            }, 1000);
        }
    } else if(type == 'callback') {//当返回类型是'callback'时
        switch(data.cmd) {
            case 'filter_upload': //filter_upload
                var filter = data.data;
                var id = filter.id;
                var has_sign = filter.has_sign;
                var filtersign = filter.filtersign;
                //$('#filter_' + id + ' td').css('background', 'red');
                if(has_sign && filtersign) {
                    $('#has_sign_' + id).html(has_sign);
                    $('#sign_file_' + id).val(filtersign);
                }
                App.Alt.FlashFcous('#filter_' + id + ' td');
                break;
            case 'banner_edit': //banner_edit
                var banner = data.data;
                var id = banner.id;
                var name = banner.name;
                var url = banner.url;
                var img_url = banner.banner_url;
                $('#name_' + id).html(name);
                $('#url_' + id).html(url);
                $('#img_' + id + ' img').attr('src', img_url);
                $('#img_url_' + id).val(img_url);
                App.Alt.FlashFcous('#banner_' + id + ' td');
                break;
        }
    } else {
        //什么也不做
    }
    callback();
};
/**
 * 父域接收器
 * @param string|int parent_id ID
 * @param string type 标识接收器处理类型
 * @param Object data 数据
 * @param Object option 选项
 * @param Function callback 回调函数
 */
Acceptor.fromParent = function(parent_id, data, option, callback) {
    
};
/**
 * JSONP形式的接收器
 * @param Array args 参数数据数组
 */
Acceptor.jsonp = function(args) {
    console.log(args);
};