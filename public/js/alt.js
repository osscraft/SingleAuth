var Alt = App.Alt = {};

Alt.success = function(type, timer) {
    var SuccussArray = ['Success!', 'Send Success!', 'Delete Success!', 'Operation Success!'];
    Cms.base('false', '400', '60');
    $("#MaskTxtId").parent().css({
        "opacity" : 0.95
    });
    $("#MaskTxtId").html('<div class="cenalig font_14" >' + SuccussArray[type] + '</div>');
    Cms.Clear(timer);
};
Alt.warning = function(type, timer) {
    var SuccussArray = ['The content exists,please chec it!', 'Send Failure!', 'Delete Failure!', 'Operation Failure!'];
    Cms.base('false', '430', '100');
    $("#MaskTxtId").parent().css({
        "opacity" : 0.95
    });
    $("#MaskTxtId").html('<div class="cenalig font_14" >' + SuccussArray[type] + '</div>');
    Cms.Clear(timer);
};
Alt.successInfo = function(type, info, timer) {
    var SuccussArray = ['Success!', 'Send Success!', 'Delete Success!', 'Operation Success!'];
    Cms.base('false', '430', '100');
    $("#MaskTxtId").parent().css({
        "opacity" : 0.95
    });
    $("#MaskTxtId").html('<div class="cenalig font_14" >' + SuccussArray[type] + '(' + info + ')</div>');
    Cms.Clear(timer);
};
Alt.warningInfo = function(type, info, timer) {
    var SuccussArray = ['The content exists,please chec it!', 'Send Failure!', 'Delete Failure!', 'Operation Failure!'];
    Cms.base('false', '430', '100');
    $("#MaskTxtId").parent().css({
        "opacity" : 0.95
    });
    $("#MaskTxtId").html('<div class="cenalig font_14" >' + SuccussArray[type] + '(' + info + ')</div>');
    Cms.Clear(timer);
};
Alt.info = function(info, timer) {
    Cms.base('false', '430', '100');
    $("#MaskTxtId").parent().css({
        "opacity" : 0.95
    });
    $("#MaskTxtId").html('<div class="cenalig font_14" >' + info + '</div>');
    Cms.Clear(timer);
};
/**
 * @param string selector 选择器
 * @param number timer 样式保持超时时间
 * @param string element 可选项,CSS元素,默认为'background'
 * @param string value 可选项,CSS元素值,默认为'red'
 * @param Object option 可选项,选项，暂无参数项
 * @param Function callback 可选项，结束回调函数
 */
Alt.focus = function(selector, timer, element, value, option, callback) {
    timer = _.isNumber(timer) ? timer : 1;
    element = _.isUndefined(element) ? 'background' : element;
    value = _.isUndefined(value) ? 'red' : value;
    option = _.isUndefined(option) ? {} : option;
    callback = _.isFunction(callback) ? callback : function() {};
    var raw = $(selector).css(element);
    
    $(selector).css(element, value);
    setTimeout(function() {
        $(selector).css(element, raw);
        callback();
    }, timer * 1000);
};
/**
 * @param string selector 选择器
 * @param number timer 闪烁超时时间
 * @param number gap 闪烁间隙
 * @param string element 可选项,CSS元素,默认为'background'
 * @param string value 可选项,CSS元素值,默认为'red'
 * @param Object option 可选项,选项，暂无参数项
 * @param Function callback 可选项，结束回调函数
 */
Alt.flash = function(selector, timer, gap, element, value, option, callback) {
    timer = _.isNumber(timer) ? timer : 1;
    gap = _.isNumber(gap) ? gap : 0.1;
    element = _.isUndefined(element) ? 'background' : element;
    value = _.isUndefined(value) ? 'red' : value;
    option = _.isUndefined(option) ? {} : option;
    callback = _.isFunction(callback) ? callback : function() {};
    var raw = null;
    var israw = false;
    var isattr = option.attr ? true : false;
    var restore = option.restore ? true : false;

    if(isattr) {
    	raw = $(selector).attr(element);
    	$(selector).attr(element, value);
    } else {
    	raw = $(selector).css(element);
    	$(selector).css(element, value);
    }
    var tmp = setInterval(function() {
        if(israw && isattr) {
            $(selector).attr(element, value);
        } else if(isattr) {
            $(selector).attr(element, raw);
        } else if(israw) {
            $(selector).css(element, value);
        } else {
            $(selector).css(element, raw);
        }
        israw = !israw;
    }, gap * 1000);
    setTimeout(function() {
        clearInterval(tmp);
        if(isattr && restore) {
        	$(selector).attr(element, raw);
        } else if(restore) {
        	$(selector).css(element, raw);
        } else if(isattr) {
        	$(selector).attr(element, value);
        } else {
        	$(selector).css(element, value);
        }
        callback();
    }, timer * 1000);
};
/**
 * @param string selector 选择器
 * @param number timer 闪烁超时时间
 * @param number gap 闪烁间隙
 * @param number coefficient 样式保持超时时间与闪烁超时时间的系数
 * @param string element 可选项,CSS元素,默认为'background'
 * @param string color 可选项,色彩,默认为'red'
 * @param Object option 可选项,选项，暂无参数项
 * @param Function callback 可选项，结束回调函数
 */
Alt.flashFcous = function(selector, timer, gap, coefficient, element, value, option, callback) {
    timer = _.isNumber(timer) ? timer : 0.6;
    gap = _.isNumber(gap) ? gap : 0.1;
    coefficient = _.isNumber(coefficient) ? coefficient : 4;
    App.Alt.flash(selector, timer, gap, element, value, option, function() {
        App.Alt.focus(selector, timer * coefficient, element, value, option, callback);
    });
};