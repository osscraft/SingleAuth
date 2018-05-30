$(document).ready(function(){
    $("#getcode_char").attr("src", "verifyCode.php?key=verifyCode&_dr=" + Math.random() );
    //获取图片
    $("#getcode_char").click(function(){
        $(this).attr("src", "verifyCode.php?key=verifyCode&_dr=" + Math.random() );
    });
    //验证
    $("#code_char").blur(function(){
        var code_char = new String($("#code_char").val());
        
            code_char = code_char.toLowerCase();
            //url,content,data
            $.post("verifyCode.php?act=char&key=checkCode",{"verifyCode":code_char},function(msg){
                var json = eval(msg);
                if(json && json.success == "1"){
                    $("#user_info").css({"color":"green"});
                    $("#verifyCodeSuccess").val("1");
                    $("#user_info").html("验证码正确！");
                }else{
                    $("#user_info").css({"color":"red"});
                    $("#verifyCodeSuccess").val("0");
                    $("#user_info").html("验证码错误！");
                }
            });
    });
    

});

function checkUsernameAndPassword(){
    $username = $("#username").val();
    $password = $("#password").val();
    if($username == "" || $password == ""){
        $("#user_info").css({"color":"red"});
        if($username == "" ){
            $("#user_info").html("帐号不能为空！");
        }
        if($password == "" ){
            $("#user_info").html("密码不能为空！");
        }
        if($username == "" && $password == "" ){
            $("#user_info").html("账号和密码不能为空！");
        }
        return false;
    } else{
        return true;
    }
}
function checkUsername() {
    $username = $("#username").val();
    if($username == "") {
        $("#user_info").css({"color":"red"});
        $("#user_info").html("帐号不能为空！");
    }
}
function checkPassword() {
    $password = $("#password").val();
    if($password == "") {
        $("#user_info").css({"color":"red"});
        $("#user_info").html("密码不能为空！");
    }
}

function validateUsernameAndPassword(){
    $username = $("#username").val();
    $password = $("#password").val();
    if($username == "" || $password == ""){
        if($username == "" && $password == "" ){
            $("#user_info").html("账号和密码不能为空！");
            alert("账号和密码不能为空！");
        } else if($username == "" ){
            $("#user_info").html("帐号不能为空！");
            alert("帐号不能为空！");
        } else if($password == "" ){
            $("#user_info").html("密码不能为空！");
            alert("密码不能为空！");
        }
        return false;
    } else if( $("#getcode_char").attr("src") != null ){
        if( $("#verifyCodeSuccess").val() != "1"){
            alert("验证码错误！");
            return false;
        }
    } else{
        return true;
    }

}
