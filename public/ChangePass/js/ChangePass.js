$(function(){
var flag=false;
var msg='';
	$('.submit').click(function(){
		//var data=$(this).serialize();
		var data={};
		data.npassword = $("#npassword").val();
		data.opassword = $("#opassword").val();
		data.cpassword = $("#cpassword").val();
		data.key='upd';
		data.uid=$("#uid").val();
		if(checkAll()){
			$.ajax({
				url:"changePassword.php",
				method:"post",
				async:false,
				dataType:"json",
				data:data,
				success:function(databack){
					flag=databack.SUCCESS;
					if(!flag){
						$("#msg").html('修改密码失败');
					}
				}
			});
		}
		if(flag){
			$("#upd").submit();
		}
	});
	$("#opassword").blur(function(){
		checkopassword(); //location.href="changePassword.php?opassword="+data.password+"&uid="+data.uid;
	});
	$("#npassword").blur(function(){
		checknpassword();
	});
	$("#cpassword").blur(function(){
		checkcpassword();
	});
	$("#opassword,#npassword,#cpassword").focus(function(){
		$("#msg").html('');
	});	
});
function checkcpassword(){
	var data={};
	data.npassword = $("#npassword").val();
	data.cpassword = $("#cpassword").val();
	if(data.cpassword==''){
		$("#cmsg").html ('确认密码不能为空');
		return false;
	}else if(data.cpassword!=data.npassword){
		$("#cmsg").html ('两次密码输入不一致');
		return false;
	}else{
		$("#cmsg").html ('&nbsp;');
	}
	return true;
}
function checknpassword(){
	var data={};
	data.npassword = $("#npassword").val();
	if(data.npassword==''){
		$("#nmsg").html ('新密码不能为空');
		return false;
	}else{
		$("#nmsg").html ('&nbsp;');
	}
	return true;
}
function checkopassword(){
	var data={};
	data.password = $("#opassword").val();
	data.uid=$("#uid").val();
	data.key='check';
	var flag=false;
	if(data.password==''){
		$("#omsg").html ('原始密码不能为空');
	}else{
		$.ajax({
			url:"changePassword.php",
			method:"post",
			async:false,
			dataType:"json",
			data:data,
			success:function(databack){
				flag=databack.SUCCESS;
				if(!flag){
					$("#opassword").val("");
					$("#omsg").html ('原始密码输入错误');
				}else{
					$("#omsg").html ('&nbsp;');
				}
			}
		});
	}
}
function checkAll(){
	var data={};
	data.password = $("#opassword").val();
	if(data.password==''||!checknpassword()||!checkcpassword()){
		return false;
	}else{
		return true;
	}
}