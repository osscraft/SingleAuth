var App = {};

App.name = 'App';
App.year = (new Date).getFullYear();
App.font = 'RobotoDraft';
App['default'] = '#e2e2e2';
App.textColor = '#6B6B6B';
App.white = 'white';
App.border = '#e4e4e4';
App.dark = '#4C5064';
App.danger = '#d96557';
App.info = '#4cc3d9';
App.success = '#2ECC71';
App.warning = '#ffc65d';
App.bodyBg = '#e0e8f2';
App.primary = '#09c';
App.dblue = 'rgb(57,137,209)';
App.standard = '#A00B0F';
App.defaultTitle = "提示";
App.base = function(isMask, owidth, oheight, title, connextId) {
    App.Close();
    if($.browser.msie) {
        isMask = "maskTitle";
    }// 为IE加MASK
    var divString = '';
    var docHeight = $(document).height();
    if(isMask == "true" || isMask == "maskTitle") {
        if($.browser.msie && ($.browser.version < "7.0")) {
            divString = '<div id="maskId" class="mask" style="width:100%; display:none; height:' + docHeight
                    + 'px"><iframe width="100%" height=' + docHeight
                    + ' frameborder=0 id="ifram"></iframe></div>';
        } else {
            divString = '<div id="maskId" class="mask" style="width:100%; display:none; height:' + docHeight
                    + 'px"></div>';
        }
    } else {
        divString = '';
    }
    divString += '<div class="Altdiv" id="maskBoxId" style="left:0; top:0;position:absolute; width:' + owidth
            + 'px; display:none; height:' + oheight + 'px;">';

    if(title) {
        divString += '<div class="Alttit"><a class="close_ab" href="javascript:void(0);" onclick="App.Close()"><img src="/images/close.jpg" alt="关闭" title="关闭" /></a>'
                + title + '</div>';
    }
    divString += '<div id="MaskTxtId" class="AltdivC"></div></div>';
    $("body").append(divString);
    if(connextId) {
        var getC = $("#" + connextId).html();
        $("#MaskTxtId").html(getC);
    }
    var windowWidth = $(window).width(), windowHeight = $(window).height(), getMsBoxWidth = $("#maskBoxId").width(), getMsBoxHeight = $(
            "#maskBoxId").height(), getScrollTop = $(document).scrollTop(), MBLeft = (windowWidth / 2)
            - (getMsBoxWidth / 2), MBTop = getScrollTop + windowHeight / 2 - getMsBoxHeight / 2;
    MBTop = (MBTop < 0) ? 0 : MBTop;
    $("#maskBoxId").css({
        "left" : MBLeft,
        "top" : MBTop
    });
    $("#maskId").show();
    $("#maskBoxId").show();
};
App.Close = function() {
    ($("#ifram").attr("id") == "ifram") ? ($("#ifram").remove()) : "";
    ($("#maskId").attr("id") == "maskId") ? ($("#maskId").remove()) : "";
    ($("#maskBoxId").attr("id") == "maskBoxId") ? ($("#maskBoxId").remove()) : "";
};
App.Clear = function(timer) {
    var time = (timer && timer != "") ? timer : 2;
    setTimeout(function() {
        App.Close();
    }, time * 1000);
};

// fixed ie console error
if (typeof console == "undefined") {
    this.console = {log: function (msg) {}};
}
//
$(document).ready(function() {
    moment.locale('zh-cn');
});