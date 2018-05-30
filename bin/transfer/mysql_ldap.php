#!/usr/bin/php
<?php
$options = getopt("a:b:c:d:e:");
/*$a="delete";
$b="lishan";
$c="lishan";
$d="other";
$e="lishan";*/
$options = getopt("a:b:c:d:e:");
$a=$options['a'];
$b=$options['b'];
$c=$options['c'];
$d=$options['d'];
$e=$options['e'];
if ($d =="教师" ){
    $ou="teacher";
}elseif ($d == "学生"){
    $ou="student";
}else {
    $ou="other";
}
$host="192.168.0.23";
$username="cn=admin,dc=ldap,dc=dcux,dc=com";
$password="";
$ds=ldap_connect($host);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
$bind=@ldap_bind($ds ,$username ,$password)or die(ldap_error($ds));
if($a=="add"){
	$dn="userid=$b,ou=$ou,o=sso,dc=ldap,dc=dcux,dc=com";
	$password="{md5}".(base64_encode(pack('H*', md5($c))));
	$info=array("userid"=>$b,"userPassword"=>$password,"role"=>$d,"username"=>$e,"objectClass"=>array("top","user"));
	$dn1="cn=$b,ou=024,o=centerDB,dc=ldap,dc=dcux,dc=com";
	$info1=array("cn"=>$b,"userPassword"=>$password,"sn"=>$e,"employeeType"=>$d,"objectClass"=>array("top","person","organizationalPerson","inetOrgPerson"));
	$flag=ldap_add($ds,$dn,$info)&&ldap_add($ds,$dn1,$info1);
	if($flag){
		write('add.ldif',$dn."\n".$dn1);
	}
}else if($a=="delete"){
	$dn="o=centerDB,dc=ldap,dc=dcux,dc=com";
	$dn1="o=sso,dc=ldap,dc=dcux,dc=com";
	$cn="cn=$b";
	$cn1="userid=$b";
	$result=@ldap_search($ds,$dn,$cn,array("cn","sn","employeeType"));
	$ldapResult = ldap_get_entries($ds, $result);
	$result1=@ldap_search($ds,$dn1,$cn1,array("userid","username","role"));
	$ldapResult1 = ldap_get_entries($ds, $result1);
	$flag=ldap_delete($ds,$ldapResult[0]['dn'])&&ldap_delete($ds,$ldapResult1[0]['dn']);
	if($flag){
		write('delete.ldif',$ldapResult[0]['dn']."\n".$ldapResult1[0]['dn']);
	}
}
ldap_close($ds);
function write($filename,$content){
	if( ($filename=fopen ($filename,"a")) === FALSE){ 
		echo("创建可写文件：".$filename."失败");   
		exit();
	}
	if(!fwrite ($filename,$content."\n")){ //将信息写入文件
        echo ("尝试向文件".$filename."写入".$content."失败！");
        fclose($filename);
        exit();       
	}  
	fclose ($filename); 
}
?>