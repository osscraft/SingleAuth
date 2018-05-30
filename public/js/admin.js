//
var Admin = {};

$(document).ready(function() {
    $("#searchClientId").focusin(function(e) {
        if($("#searchClientId").attr("value") == "输入客户端标识符或名称") {
            $("#searchClientId").attr("value","");
        }
    });
    $("#searchClientId").focusout(function(e) {
        if($("#searchClientId").attr("value") == "") {
            $("#searchClientId").attr("value","输入客户端标识符或名称");
        }
    });
    $("#searchClientIdBtn").click(function(e) {
        var clientId=$('#searchClientId').attr('value');
        window.location='client.php?key=search&clientId='+clientId;
    });

    bootbox.setLocale('zh_CN');
});

Admin.viewClientById = $.viewClientById = function(id) {
    window.location='client.php?key=view&id=' + id;
};
Admin.statClientById = $.statClientById = function(id) {
    window.location='client.php?key=stat&id=' + id;
};
Admin.modifyClientById = $.modifyClientById = function(id) {
    window.location='client.php?key=tomodify&id=' + id;
};
Admin.deleteClientById = $.deleteClientById = function(id,clientId,clientName,clientSecret) {
    if(confirm("确认删除" + clientName + "？")) {
        window.location="client.php?key=delete&id=" + id + "&clientId=" + clientId + "&clientSecret=" + clientSecret + "";
    }
};
Admin.geneClientSecret = $.geneClientSecret = function() {
    var t = new Date();
    var code = window.hex_md5('' + Math.random() + t.getTime());
    $("#clientSecret").attr("value",code);
    $("#clientSecretText").html(code);
};
Admin.submitClient = $.submitClient = function() {
    if($.checkClient()) {
        $("form").submit();
    } else {
        alert("客户端不正确！");
    }
};
Admin.checkClient = $.checkClient = function() {
    var clientId = $("#clientId").attr("value");
    var clientName = $("#clientName").attr("value");
    var clientSecret = $("#clientSecret").attr("value");
    if(!clientId || !clientName || !clientSecret) {
        return false;
    } else {
        return true;
    }
};
var submitUser = Admin.submitUser = function submitUser(form) {
    if(checkUser(form)) {
        form.submit();
    } else {
        alert("用户不正确！");
    }
};
var submitClient = Admin.submitClient = function submitClient(form) {
    if(checkClient(form)) {
        form.submit();
    } else {
        alert("客户端不正确！");
    }
};
var submitLDAPConfig = Admin.submitLDAPConfig = function submitLDAPConfig(form) {
    if(checkLDAPConfig(form)) {
        form.submit();
    } else {
        alert("LDAP配置不正确！");
    }
};
var submitSetting = Admin.submitSetting = function submitSetting(form) {
    if(checkSetting(form)) {
        form.submit();
    } else {
        alert("配置不正确！");
    }
};
var submitSkin = Admin.submitSkin = function submitSkin(el) {
    var form = $(el).closest('form');
    form.submit();
}
var checkUser = Admin.checkUser = function checkUser(form) {
    var uid = document.getElementsByName("uid")[0];
    var username = document.getElementsByName("username")[0];
    var isAdmin = document.getElementsByName("isAdmin")[0];
    if(!uid.value || !username.value) {
        return false;
    } else {
        return true;
    }
};
var checkSetting = Admin.checkSetting = function checkUser(form) {
    var k = document.getElementsByName("k")[0];
    var v = document.getElementsByName("v")[0];
    if(!k.value || !v.value) {
        return false;
    } else {
        return true;
    }
};
var checkClient = Admin.checkClient = function checkClient(form) {
    var clientId = document.getElementsByName("clientId")[0];
    var clientName = document.getElementsByName("clientName")[0];
    var clientSecret = document.getElementsByName("clientSecret")[0];
    if(!clientId.value || !clientName.value || !clientSecret.value) {
        return false;
    } else {
        return true;
    }
};
var checkLDAPConfig = Admin.checkLDAPConfig = function checkLDAPConfig(form) {
    var host = document.getElementsByName("host")[0];
    var baseDN = document.getElementsByName("baseDN")[0];
    var rootDN = document.getElementsByName("rootDN")[0];
    var rootPW = document.getElementsByName("rootPW")[0];
    if(!host.value || !baseDN.value) {
        return false;
    } else {
        return true;
    }
};
